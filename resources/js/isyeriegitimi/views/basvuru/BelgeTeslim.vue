<template>
    <div>
        <v-snackbar top :color="color" :timeout="timeout" v-model="snackbar">
            {{ snackbarMessage }}
        </v-snackbar>
        <div class="container">
            <v-card>
                <v-card-title>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-account-search"
                        label="Kelimeye Göre Filtrele"
                        outlined
                        hide-details>
                    </v-text-field>
                    <v-divider
                        class="mx-6"
                        inset
                        vertical
                    >
                    </v-divider>
                    <v-select
                        v-model="searchBelgeDurum"
                        :items="belgeDurumList"
                        label="Belge Durumuna Göre Filtrele"
                        outlined
                        hide-details
                    >
                    </v-select>
                </v-card-title>
                <v-card-title>
                    <v-btn-toggle
                        v-model="basvuruBelgeTeslimFiltre"
                        mandatory
                        group
                        color="blue-grey darken-2"
                    >
                    <v-btn :value="true">
                        Belge Teslim Süresi Devam Eden Öğrenciler
                    </v-btn>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-btn :value="false">
                        Belge Teslim Tarihlerini Kaçıran Öğrenciler
                    </v-btn>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-btn value="hepsi">
                        Hepsi
                    </v-btn>
                    </v-btn-toggle>
                </v-card-title>
                <v-data-table
                    :headers="headers"
                    :items="Items"
                    class="elevation-1"
                    sort-by="id"
                    :search="search"
                    :items-per-page="rowsPerPage"
                    :loading="loading" loading-text="İşlem Yapılıyor, Lütfen Bekleyin"
                    show-expand
                    single-expand
                    dense
                >
                    <template v-slot:top>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>AKTİF BAŞVURULAR</v-toolbar-title>
                            <v-spacer></v-spacer>

                            <v-dialog v-model="dialog" max-width="90%">
                                <v-card>
                                    <v-card-title>
                                        <span class="headline">{{ formTitle }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-form v-model="dialogValid" ref="dialogform">
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            color="blue-grey lighten-2"
                                                            v-model="editedItem.firmayetkilisi"
                                                            :items="firmaYetkiliList"
                                                            hide-no-data
                                                            hide-selected
                                                            item-text="user.name"
                                                            item-value="id"
                                                            label="Firma Yetkilisi Adı"
                                                            return-object
                                                            persistent-hint
                                                            outlined
                                                            :rules="stringRules"
                                                        ></v-autocomplete>
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
                    <template v-slot:item.basvuru_belge_teslim="{ item }">
                        <v-switch
                            v-model="item.basvuru_belge_teslim"
                            false-value="bekleniyor"
                            true-value="teslim edildi"
                            @change="checkDocuments(item)">
                        </v-switch>
                    </template>
                    <template v-slot:item.basvuru_belge_tarihi="{ item }">
                        <v-icon
                            v-if="item.basvuru_belge_tarihi"
                            color="green"
                        >
                            mdi-checkbox-marked-circle
                        </v-icon>
                        <v-icon
                            v-else
                            color="red"
                        >
                            mdi-cancel
                        </v-icon>
                    </template>
                    <template v-slot:item.cumartesi="{ item }">
                        <v-checkbox
                            v-model="item.cumartesi"
                            :disabled="true"
                            >
                        </v-checkbox>
                    </template>
                    <template v-slot:item.firmayetkilisi="{ item }">
                        <span
                            v-if="item.firmayetkilisi"
                            v-html="item.firmayetkilisi.name"
                        >
                        </span>
                        <v-btn
                        small
                        @click="editItem(item)"
                        icon
                        >
                        <v-icon dark>mdi-pencil</v-icon>
                        </v-btn>
                    </template>
                    <template v-slot:item.action="{ item }">
                        <v-btn
                        small
                        color="deep-orange"
                        class="ma-2 white--text"
                        fab
                        @click="deleteItem(item)"
                        >
                        <v-icon dark>mdi-delete</v-icon>
                        </v-btn>
                    </template>
                    <template v-slot:expanded-item="{ headers, item }">
                        <td :colspan="headers.length" class="pa-2">
                                Öğrenci Detay<br>
                                mail: {{ item.user.email }}<br>
                                kayıt şekli: {{ item.user.ogrenci.kayit_sekli }}<br>
                                kayıt yılı: {{ item.user.ogrenci.kayit_yili }}<br>
                                telefon: {{ item.user.ogrenci.telefon }}
                        </td>
                    </template>
                    <template v-slot:no-data>
                        <v-btn color="primary" @click="getBasvurular">Reset</v-btn>
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
            belgeDurumList: [
                'hepsi',
                'bekleniyor',
                'teslim edildi'
            ],
            searchBelgeDurum: '',
            rowsPerPage: 25,
            menu: false,
            active: null,
            dialog: false,
            show: false,
            selectUser: '',
            newPassword: '',
            passwordConfirmation: '',
            buttonLoader: false,
            validForm: true,
            dialogValid: true,
            passwordModal: false,
            loading: null,
            loadingmail: false,
            color: 'success',
            snackbar: false,
            snackbarMessage: '',
            timeout: 2000,
            stringRules: [
            v => !!v || 'Bu alan gereklidir',
            ],
            emailRules: [
                v => !!v || 'E-mail alanı gereklidir',
                v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail formatı yanlış',
            ],
            Items: [],
            editedIndex: -1,
            editedItem: {
                id: 0,
                belge_adi: '',
                belge_yolu: null,
                belge_aciklama: '',
                status: "active",
            },
            defaultItem: {
                id: 0,
                belge_adi: '',
                belge_yolu: null,
                belge_aciklama: '',
                status: "active",
            },
            file: '',
            firmaYetkiliList: [],
            start_date: '',
            end_date: '',
            basvuruBelgeTeslimFiltre: null
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'Yeni Kayıt' : 'Kayıt Düzenle'
            },
            headers() {
                return [
                    { text: 'NO', value: 'user.ogrenci.ogrenci_no' },
                    { text: 'ÖĞRENCİ', value: 'user.name' },
                    { text: 'BAŞLANGIÇ TARİHİ', value: 'baslangic_tarihi' },
                    { text: 'BİTİŞ TARİHİ', value: 'bitis_tarihi' },
                    { text: 'CUMARTESİ ÇALIŞMA', value: 'cumartesi' },
                    { text: 'FİRMA ADI', value: 'firma.adi' },
                    { text: 'FİRMA YETKİLİSİ ADI', value: 'firmayetkilisi' },
                    {
                        text: 'BAŞVURU BELGELERİ DURUM',
                        value: 'basvuru_belge_teslim',
                        filter: value => {
                            if (!this.searchBelgeDurum) return true;
                            if (this.searchBelgeDurum==='hepsi') return true;
                            return value === this.searchBelgeDurum
                        },
                    },
                    {
                        text: 'TESLİM TARİHİ',
                        value: 'basvuru_belge_tarihi',
                        filter: value => {
                            if (this.basvuruBelgeTeslimFiltre==="hepsi") return true;
                            return value === this.basvuruBelgeTeslimFiltre
                        },
                    },
                    { text: 'İŞLEM', value: 'action', sortable: false },
                ]
            }
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
        },

        created () {
            this.getBasvurular();
        },

        methods: {
            getBasvurular () {
                api.get('/isyeriegitimi/basvuru-sureci/belgeler')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.basvurular;
                        }
                    })
                    .catch(err => {
                         setTimeout(() => {
                            this.color = 'error';
                            this.snackbar = true;
                            this.snackbarMessage = err.response.data.message;
                        }, 1000);
                    });
            },
            calcEndDate(baslangic_tarihi){
                return moment(baslangic_tarihi).subtract(30, 'days').format("YYYY-MM-DD");
            },
            editItem (item) {
                this.editedIndex = this.Items.indexOf(item);
                this.editedItem = Object.assign({}, item);
                api.get('/isyeriegitimi/bitis-sureci/firmayetkilileri/' + item.firma.id)
                    .then(response => {
                        if (response.data.success) {
                            this.firmaYetkiliList = response.data.data.firma_yetkilileri;
                        }
                    })
                    .catch(err => {
                         setTimeout(() => {
                            this.color = 'error';
                            this.snackbar = true;
                            this.snackbarMessage = 'Bu Firmaya Ekli Bir Yetkili Bulunmamaktadır.';
                        }, 1000);
                    });

                this.dialog = true
            },

            deleteItem (item) {
                if (confirm('Bu başvuruyu silmek istediğinize emin misiniz?')){
                    this.loader = true;
                    let index = this.Items.indexOf(item);

                    api.delete('/isyeriegitimi/basvuru-sureci/belgeler/' + item.id).then(response => {
                        if (response.data.success) {
                            setTimeout(() => {
                                this.loader = false;
                                this.Items.splice(index, 1);
                                this.color = 'success';
                                this.snackbar = true;
                                this.snackbarMessage = "Başvuru Başarıyla Silindi";
                            }, 1000);
                        }
                    })
                }
            },
            close () {
                this.dialog = false;
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1
                }, 300)
            },
            save () {
                if (!this.$refs.dialogform.validate()) {
                    this.color = 'error';
                    this.snackbar = true;
                    this.snackbarMessage = "İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz";
                    return false;
                }

                let index = this.editedIndex;

                api.put('/isyeriegitimi/basvuru-sureci/yonetim/' + this.editedItem.id, JSON.stringify(this.editedItem))
                    .then(response => {
                        if(response.data.success) {
                            let item = response.data.data;
                            Object.assign(this.Items[index], item);
                            this.color = 'success';
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarılı';
                            this.$refs.dialogform.resetValidation();
                            this.close()
                        }
                    })
                    .catch(error => {
                        this.color = 'error';
                        this.snackbar = true;
                        this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                    })


            },
            checkDocuments(item){
                api.put('/isyeriegitimi/basvuru-sureci/belgeler/' + item.id + '/toggle-documents')
                    .then(response => {
                        if(response.data.success) {
                            this.color = 'success';
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarılı';
                            item.basvuru_belge_tarihi = response.data.data.basvuru_belge_tarihi;
                        }
                    })
                    .catch(error => {
                        this.color = 'error';
                        this.snackbar = true;
                        this.snackbarMessage = 'Başvuru Tamamlanamıyor, Tekrar İnceleyiniz.';
                        item.basvuru_belge_teslim = 'bekleniyor';
                    })
            },

        },
    }
</script>
