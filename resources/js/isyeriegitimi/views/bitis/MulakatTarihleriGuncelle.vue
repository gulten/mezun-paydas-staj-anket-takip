<template>
  <div>
      <v-card flat outlined class="mb-12 grey lighten-5" min-height="200px">
        <v-card-title>
            İŞYERİ EĞİTİMİ MÜLAKAT TARİHLERİNİ GÜNCELLE
        </v-card-title>
        <v-card-text>
            <v-form>
                <v-row>
                    <v-col cols="12" sm="6">
                        <v-menu
                            v-model="menu"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                <v-text-field
                                    v-model="baslamaTarihi"
                                    label="Başlangıç Tarihi"
                                    prepend-icon="mdi-calendar-blank-outline"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="baslamaTarihi"
                                    @input="menu = false"
                                    locale="tr"
                                    :max="bitisTarihi"
                                    >
                                </v-date-picker>
                        </v-menu>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-menu
                            v-model="menu2"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                <v-text-field
                                    v-model="bitisTarihi"
                                    label="Bitiş Tarihi"
                                    prepend-icon="mdi-calendar-blank-outline"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="bitisTarihi"
                                    @input="menu2 = false"
                                    locale="tr"
                                    :min="baslamaTarihi"
                                    >
                                </v-date-picker>
                        </v-menu>
                    </v-col>
                </v-row>
            </v-form>
        </v-card-text>
    </v-card>
    <v-btn color="primary" @click="save">Güncelle</v-btn>

    <v-snackbar
            v-model="snackbar"
            :color="color"
            :top='true'
        >
            {{ snackbarMessage }}
            <v-btn
                dark
                text
                @click="snackbar = false"
            >
                Kapat
            </v-btn>
        </v-snackbar>

  </div>
</template>

<script>
export default {
    data () {
      return {
        menu: false,
        menu2: false,
        baslamaTarihi: '',
        bitisTarihi: '',
        snackbarMessage: 'Bir sorun oluştu',
        snackbar: false,
        color: 'general',
      }
    },
    created () {
        this.getDates();
    },
    methods: {
        getDates () {
            api.get('/isyeriegitimi/mulakat-tarihleri/guncelle')
                .then(response => {
                    if (response.data.success) {
                        this.baslamaTarihi = response.data.data.islemtarih.baslangic_tarihi;
                        this.bitisTarihi = response.data.data.islemtarih.bitis_tarihi;
                    }
                });
        },
        save() {
        let requestBody = {
                baslangic_tarihi: this.baslamaTarihi,
                bitis_tarihi: this.bitisTarihi
            }
            api.post('/isyeriegitimi/mulakat-tarihleri/guncelle', JSON.stringify(requestBody))
                .then(response => {
                    if (response.data.success) {
                        this.color = 'success';
                        this.snackbar = true;
                        this.snackbarMessage = 'Tarihler Başarıyla Güncellendi';
                    }
                })
                .catch(err => {
                    this.color = 'error';
                    this.snackbar = true;
                    this.snackbarMessage = 'Bir Sorun Oluştu, Lütfen Tarihleri Kontrol Ediniz';
                });
        }
    }
}
</script>

<style>

</style>
