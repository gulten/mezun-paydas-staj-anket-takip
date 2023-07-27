<template>
    <div>
        <v-snackbar top :timeout="timeout" v-model="snackbar">
            {{ snackbarMessage }}
        </v-snackbar>
        <v-dialog
            v-model="dialogDersler"
            max-width="90%"
            >
            <v-card>
                <v-card-title class="headline text-center">Öneri Sunacağınız Dersi Seçiniz</v-card-title>
                <v-card-text class="mt-3 mb-3">
                        <v-data-table
                            :headers="headersDersler"
                            :items="tumDersler"
                            :items-per-page="15"
                        >
                        <template v-slot:item.action="{ item }">
                            <v-btn
                                small
                                color="teal"
                                class="ma-2 white--text"
                                :disabled="item.bolumeoneri?true:false"
                                @click="[ editedItem.ders_id = item.id, editedItem.bolumders = item, dialogDersler = false]"
                                >
                                <v-icon dark>mdi-attachment</v-icon> Seç
                            </v-btn>
                        </template>
                        </v-data-table>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="green darken-1"
                    text
                    @click="dialogDersler = false"
                >
                    Kapat
                </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <div class="container">
            <v-card>
                <v-card-title>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-account-search"
                        label="Arama"
                        single-line
                        hide-details>
                    </v-text-field>
                </v-card-title>
                <v-data-table
                    :headers="headers"
                    :items="Items"
                    class="elevation-1"
                    sort-by="id"
                    :search="search"
                    :items-per-page="rowsPerPage"
                >
                    <template v-slot:top>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>BÖLÜME ÖNERİLER</v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
                            <v-spacer></v-spacer>
                            <v-dialog v-model="dialog" max-width="90%">
                                <template v-slot:activator="{ on }">
                                    <v-btn color="primary" dark class="mb-2" v-on="on">Yeni Kayıt</v-btn>
                                </template>
                                <v-card>
                                    <v-card-title>
                                        <span class="headline">{{ formTitle }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-form v-model="dialogValid" ref="dialogform">
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="8">
                                                        <v-autocomplete
                                                            v-model="editedItem.ders_id"
                                                            :items="tumDersler"
                                                            dense
                                                            label="Seçilen Ders"
                                                            item-value="id"
                                                            item-text="ders_adi"
                                                            :rules="stringRules"
                                                            disabled
                                                            chips
                                                            outlined
                                                        >
                                                        <v-icon slot="append" color="green">mdi-arrow-right-drop-circle</v-icon>
                                                        </v-autocomplete>
                                                    </v-col>
                                                    <v-col cols="4">
                                                        <v-btn
                                                            color="primary"
                                                            dark
                                                            @click="dialogDersler = true"
                                                        >
                                                            Ders Listesi
                                                        </v-btn>
                                                    </v-col>
                                                    <v-col cols="12">
                                                        <v-textarea
                                                            v-model="editedItem.amaca_yonelik"
                                                            label="AMACA YÖNELİK ÖNERİLER"
                                                            outlined
                                                            required>
                                                        </v-textarea>
                                                    </v-col>
                                                    <v-col cols="12">
                                                        <v-textarea
                                                            v-model="editedItem.icerige_yonelik"
                                                            label="İÇERİĞE YÖNELİK ÖNERİLER"
                                                            outlined
                                                            required>
                                                        </v-textarea>
                                                    </v-col>
                                                    <v-col cols="12">
                                                        <v-textarea
                                                            v-model="editedItem.ders_yariyilina_saatine_yonelik"
                                                            label="DERS YARIYILINA VE SAATİNE YÖNELİK ÖNERİLER"
                                                            outlined
                                                            required>
                                                        </v-textarea>
                                                    </v-col>
                                                    <v-col cols="12">
                                                        <v-textarea
                                                            v-model="editedItem.diger"
                                                            label="DİĞER"
                                                            outlined
                                                            required>
                                                        </v-textarea>
                                                    </v-col>
                                                    <v-col cols="12">
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-form>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" text @click="close">Kapat</v-btn>
                                        <v-btn color="blue darken-1" text @click="save">Kaydet</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>
                    </template>
                    <template v-slot:item.action="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editItem(item)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deleteItem(item)"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                    <template v-slot:no-data>
                        <v-btn color="primary" @click="getOneriler">Reset</v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </div>
    </div>
</template>
<script>
    export default {
        components:{
        },
        data: () => ({
            search: '',
            rowsPerPage: 25,
            menu: false,
            menu1: false,
            active: null,
            dialog: false,
            dialogDersler: false,
            show: false,
            selectUser: '',
            buttonLoader: false,
            validForm: true,
            dialogValid: true,
            tumDersler: [],
            snackbar: false,
            snackbarMessage: '',
            timeout: 2000,
            stringRules: [
            v => !!v || 'Bu alan gereklidir',
            ],
            headers: [
                { text: 'DERS ADI', value: 'bolumders.ders_adi' },
                { text: 'AMACA YÖNELİK ÖNERİLER', value: 'amaca_yonelik' },
                { text: 'İÇERİĞE YÖNELİK ÖNERİLER', value: 'icerige_yonelik' },
                { text: 'DERS YARIYILINA VE SAATİNE YÖNELİK ÖNERİLER', value: 'ders_yariyilina_saatine_yonelik' },
                { text: 'DİĞER GÖRÜŞLER', value: 'diger' },
                { text: 'TARİH', value: 'created_at' },
                { text: 'İŞLEM', value: 'action', sortable: false },
            ],
            Items: [],
            editedIndex: -1,
            editedItem: {
                id: 0,
                ders_id: 0,
                amaca_yonelik: '',
                icerige_yonelik: '',
                ders_yariyilina_saatine_yonelik: '',
                diger: '',
                bolumders:{}
            },
            defaultItem: {
                id: 0,
                ders_id: 0,
                amaca_yonelik: '',
                icerige_yonelik: '',
                ders_yariyilina_saatine_yonelik: '',
                diger: '',
                bolumders:{}
            },
            headersDersler: [
                { text: 'SINIF', value: 'sinif'},
                { text: 'DONEM', value: 'donem' },
                { text: 'DURUM', value: 'durum' },
                { text: 'DERS KODU', value: 'ders_kodu' },
                { text: 'DERS ADI', value: 'ders_adi' },
                { text: 'HAFTALIK DERS SAATİ (Teorik + Uygulama/Lab)', value: 'haftalik_ders_saati', align: 'center', width: '15%' },
                { text: 'AMAÇ', value: 'amac' },
                { text: 'İÇERİK', value: 'icerik' },
                { text: 'İŞLEM', value: 'action', sortable: false },
            ],
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'Yeni Kayıt' : 'Kayıt Düzenle'
            },
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
        },

        created () {
            this.tumDersleriGetir();
            this.getOneriler();
        },

        methods: {
            tumDersleriGetir(){
                api.get('/mezun/dersler-oneriler')
                    .then(response => {
                        if (response.data.success) {
                            this.tumDersler = response.data.data.dersler;
                        }
                    })
            },
            getOneriler () {
                api.get('mezun/bolume-oneriler')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.oneriler;
                        }
                    })
            },
            editItem (item) {
                this.editedIndex = this.Items.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            deleteItem (item) {
                if (confirm('Bu kaydı silmek istediğinize emin misiniz?')){
                    this.loader = true;
                    let index = this.Items.indexOf(item);

                    api.delete('/mezun/bolume-oneriler/' + item.id).then(response => {
                        if (response.data.success) {
                            setTimeout(() => {
                                this.loader = false;
                                this.Items.splice(index, 1);
                                this.snackbar = true;
                                this.snackbarMessage = "Kayıt Başarıyla Silindi";
                            }, 1000);
                            this.tumDersleriGetir();
                        }
                    })
                }
            },

            close () {
                this.dialog = false;
                this.$refs.dialogform.resetValidation();
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1
                }, 300)
            },

            save () {
                if (!this.$refs.dialogform.validate()) {
                    this.snackbar = true;
                    this.snackbarMessage = "İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz";
                    return false;

                }
                if (this.editedIndex > -1) {
                    let item = this.editedItem;
                    let index = this.editedIndex;
                    api.put('mezun/bolume-oneriler/' + this.editedItem.id, JSON.stringify(this.editedItem))
                        .then(response => {
                            if(response.data.success) {
                                Object.assign(this.Items[index], item);
                                this.snackbar = true;
                                this.snackbarMessage = 'İşlem Başarılı';
                                this.$refs.dialogform.resetValidation();
                                this.tumDersleriGetir();
                                this.close()
                            }
                        })
                        .catch(error => {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                        })

                } else {
                    let item = this.editedItem;
                    api.post('mezun/bolume-oneriler', JSON.stringify(this.editedItem))
                        .then(response => {
                            if(response.data.success) {
                                item = response.data.data;
                                this.Items.push(item);
                                this.snackbar = true;
                                this.snackbarMessage = 'İşlem Başarılı';
                                this.$refs.dialogform.resetValidation();
                                this.tumDersleriGetir();
                                this.close()
                            }
                        })
                        .catch(error => {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                        })
                }

            },
            closeModal(){
                this.$refs.form.resetValidation();
                this.buttonLoader = false;
                this.passwordModal = false;
                this.active = 0;
            },
        },
    }
</script>
