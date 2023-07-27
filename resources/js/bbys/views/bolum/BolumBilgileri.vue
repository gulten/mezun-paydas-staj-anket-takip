<template>
    <div>
        <div class="container">
            <v-card
                class="overflow-hidden"
                color="lighten-1"
            >
                <v-toolbar
                    flat
                >
                    <v-icon>mdi-account</v-icon>
                    <v-toolbar-title class="font-weight-light">Bölüm Profili</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="darken-3"
                        fab
                        small
                        @click="isEditing = !isEditing"
                    >
                        <v-icon v-if="isEditing">mdi-close</v-icon>
                        <v-icon v-else>mdi-pencil</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-form
                    ref="form"
                    v-model="valid"
                >
                    <v-card-text>
                        <v-text-field
                            :disabled="!isEditing"
                            ref="universite_adi"
                            v-model="universite_adi"
                            label="Üniversite Adı"
                        ></v-text-field>
                        <v-text-field
                            :disabled="!isEditing"
                            ref="fakulte_adi"
                            v-model="fakulte_adi"
                            label="Fakülte Adı"
                        ></v-text-field>
                        <v-text-field
                            :disabled="!isEditing"
                            ref="adi"
                            v-model="adi"
                            label="Bölüm Adı"
                        ></v-text-field>

                        <!--data picker1-->
                        <v-menu
                            ref="menu"
                            v-model="menu"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            :return-value.sync="kurulus_yili"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on }">
                                <v-text-field
                                    :disabled="!isEditing"
                                    v-model="kurulus_yili"
                                    label="Kuruluş Yılı"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="kurulus_yili" no-title scrollable locale="tr">
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" @click="menu = false">İptal</v-btn>
                                <v-btn text color="primary" @click="$refs.menu.save(kurulus_yili)">Seç</v-btn>
                            </v-date-picker>
                        </v-menu>

                        <!--data picker2-->
                        <v-menu
                            ref="menu2"
                            v-model="menu2"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            :return-value.sync="faaliyet_baslangic_tarihi"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on }">
                                <v-text-field
                                    :disabled="!isEditing"
                                    v-model="faaliyet_baslangic_tarihi"
                                    label="Faaliyet Başlangıç Yılı"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="faaliyet_baslangic_tarihi" no-title scrollable locale="tr">
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" @click="menu2 = false">İptal</v-btn>
                                <v-btn text color="primary" @click="$refs.menu2.save(faaliyet_baslangic_tarihi)">Seç</v-btn>
                            </v-date-picker>
                        </v-menu>

                        <v-text-field
                            :disabled="!isEditing"
                            ref="adres"
                            v-model="adres"
                            label="Adres"
                        ></v-text-field>

                        <v-text-field
                            :disabled="!isEditing"
                            ref="telefon"
                            v-model="telefon"
                            label="Telefon"
                            :rules="phoneRules"
                            v-mask="'#(###) ### ## ##'"
                        ></v-text-field>

                        <v-text-field
                            :disabled="!isEditing"
                            ref="email"
                            v-model="email"
                            :rules="emailRules"
                            label="E-mail"
                            required
                        ></v-text-field>

                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            :disabled="!isEditing"
                            color="success"
                            @click="save"
                        >
                            Kaydet
                        </v-btn>
                    </v-card-actions>
                    <v-snackbar
                        v-model="hasSaved"
                        :timeout="2000"
                        absolute
                        bottom
                        left
                    >
                        Bölüm Bilgileri Güncellendi
                    </v-snackbar>
                </v-form>
            </v-card>
        </div>
    </div>
</template>

<script>
    export default {
        components:{
        },
        data: () => ({
            valid: true,
            id: 0,
            universite_adi: '',
            fakulte_adi: '',
            adi: '',
            adres: '',
            kurulus_yili: new Date().toISOString().substr(0, 10),
            faaliyet_baslangic_tarihi: new Date().toISOString().substr(0, 10),
            menu: false,
            menu2: false,
            modal: false,
            telefon: '',
            email: '',
            emailRules: [
                v => !!v || 'E-mail alanı gereklidir',
                v => /.+@.+/.test(v) || 'E-mail formatı hatalıdır',
            ],
            phoneRules: [
                v => !!v || 'Telefon Alanı Gereklidir',
                v => (/^(\s)?\d{1}(\()\d{3}(\))(\s)\d{3}(\s)\d{2}(\s)\d{2}$/.test(v)||v.length==0) || 'Telefon formatı hatalıdır',
            ],
            hasSaved: false,
            isEditing: null,
            model: null,

        }),
        mounted(){
            this.getData();
        },

        methods: {
            getData () {
                api.get('/bbys/bolum-bilgileri')
                    .then(response => {
                        this.id = response.data.data.id;
                        this.universite_adi = response.data.data.universite_adi;
                        this.fakulte_adi = response.data.data.fakulte_adi;
                        this.adi = response.data.data.adi;
                        this.kurulus_yili = response.data.data.kurulus_yili;
                        this.faaliyet_baslangic_tarihi = response.data.data.faaliyet_baslangic_tarihi;
                        this.adres = response.data.data.adres;
                        this.telefon = response.data.data.telefon;
                        this.email = response.data.data.email;
                    })
                    .catch(error => console.log(error))
            },
            save () {

                let formData = new FormData();

                formData.append('universite_adi', this.universite_adi);
                formData.append('fakulte_adi', this.fakulte_adi);
                formData.append('adi', this.adi);
                formData.append('kurulus_yili', this.kurulus_yili);
                formData.append('faaliyet_baslangic_tarihi', this.faaliyet_baslangic_tarihi);
                formData.append('adres', this.adres);
                formData.append('telefon', this.telefon);
                formData.append('email', this.email);
                formData.append("_method", "PUT");

                api.post('/bbys/bolum-bilgileri/' + this.id, formData)
                    .then(response => {
                        this.isEditing = !this.isEditing;
                        this.hasSaved = true
                    })
                    .catch(error => console.log(error))

            },
        },
    }
</script>
