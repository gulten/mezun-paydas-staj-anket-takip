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
                            <v-toolbar-title>AKTİF BAŞVURULARIN BİTİŞ BELGELERİ</v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
                            <v-alert
                                v-if="bitisBelgeMessageType"
                                dense
                                outlined
                                type="error"
                            >
                                {{ bitisBelgeMessage }}
                            </v-alert>
                            <v-alert
                                v-else
                                dense
                                type="info"
                            >
                                {{ bitisBelgeMessage }}
                            </v-alert>
                        </v-toolbar>
                    </template>
                    <template v-slot:item.ogrenci_anket_id="{ item }">
                        <v-checkbox
                            v-model="item.ogrenci_anket_id"
                            :disabled="true"
                            >
                        </v-checkbox>
                    </template>
                    <template v-slot:item.yetkili_anket_id="{ item }">
                        <v-checkbox
                            v-model="item.yetkili_anket_id"
                            :disabled="true"
                            >
                        </v-checkbox>
                    </template>
                    <template v-slot:item.bitis_belge_teslim="{ item }">
                        <v-switch
                            v-model="item.bitis_belge_teslim"
                            false-value="bekleniyor"
                            true-value="teslim edildi"
                            @change="checkDocuments(item)">
                        </v-switch>
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
        <v-bottom-sheet v-model="bitisBelgeDialog" persistent>
            <v-sheet
                class="text-center"
                height="200px"
            >
                <v-btn
                class="mt-6"
                text
                color="red"
                @click="bitisBelgeDialog = !bitisBelgeDialog"
                >
                Anladım
                </v-btn>
                <div class="py-3" v-html="bitisBelgeMessage">
                </div>
            </v-sheet>
        </v-bottom-sheet>
    </div>
</template>
<script>
    import moment from 'moment';
    export default {
        components:{
        },
        data: () => ({
            search: '',
            belgeDurumList: [
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
            bitis_start_date: '',
            bitis_end_date: '',
            bitisBelgeMessage: '',
            bitisBelgeDialog: false,
            bitisBelgeMessageType: false,

        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'Yeni Kayıt' : 'Kayıt Düzenle'
            },
            headers() {
                return [
                    { text: 'NO', value: 'user.ogrenci.ogrenci_no' },
                    { text: 'ÖĞRENCİ', value: 'user.name' },
                    { text: 'ÖĞRENCİ ANKETİ', value: 'ogrenci_anket_id' },
                    { text: 'YETKİLİ ANKETİ', value: 'yetkili_anket_id' },
                    {
                        text: 'BİTİŞ BELGELERİ DURUM',
                        value: 'bitis_belge_teslim',
                        filter: value => {
                            if (!this.searchBelgeDurum) return true;
                            return value === this.searchBelgeDurum
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
                api.get('/isyeriegitimi/bitis-sureci/belgeler')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.basvurular;
                            this.getDates();
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
            getDates () {
                api.get('/isyeriegitimi/teslim-tarihleri/guncelle')
                    .then(response => {
                        if (response.data.success) {
                            this.bitis_start_date = response.data.data.islemtarih.baslangic_tarihi;
                            this.bitis_end_date = response.data.data.islemtarih.bitis_tarihi;

                            if (moment(this.bitis_end_date).isBefore(moment(), "day")) {
                                this.bitisBelgeMessage = "Bitiş Belgelerinin Son Teslim Tarihi Geçti";
                                this.bitisBelgeDialog = true;
                                this.bitisBelgeMessageType = true;
                            }
                            else {
                                this.bitisBelgeMessage = "Bitiş Belgelerinin Son Teslim Tarihi : " + this.bitis_end_date;
                                this.bitisBelgeDialog = false;
                                this.bitisBelgeMessageType = false;
                            }
                        }
                    }
                );
            },
            editItem (item) {
                this.editedIndex = this.Items.indexOf(item);
                this.editedItem = Object.assign({}, item);
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
                this.$refs.dialogform.resetValidation();
                this.$refs.myFileInput.value = ''
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1
                }, 300)
            },
            checkDocuments(item){
                api.put('/isyeriegitimi/bitis-sureci/belgeler/' + item.id + '/toggle-documents').then(response => {
                    this.color = 'success';
                    this.snackbar = true;
                    this.snackbarMessage = 'İşlem Başarılı';
                })
                .catch(err => {
                        setTimeout(() => {
                        this.color = 'error';
                        this.snackbar = true;
                        this.snackbarMessage = 'Belgeleri Tekrar Kontrol Edin, Bir Hata Oluştu';
                        item.bitis_belge_teslim = 'bekleniyor';
                    }, 1000);
                });
            },

        },
    }
</script>
