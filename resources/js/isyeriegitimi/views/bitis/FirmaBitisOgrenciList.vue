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
                </v-card-title>
                <v-data-table
                    :headers="headers"
                    :items="Items"
                    class="elevation-1"
                    sort-by="id"
                    :search="search"
                    :items-per-page="rowsPerPage"
                    :loading="loading" loading-text="İşlem Yapılıyor, Lütfen Bekleyin"
                    dense
                >
                    <template v-slot:top>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>SORUMLU OLDUĞUNUZ ÖĞRENCİLERİN LİSTESİ</v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                    </template>
                    <template v-slot:item.action="{ item }">
                        <v-btn
                        small
                        :color="item.yetkili_anket_id?'blue-grey':'red darken-3'"
                        class="ma-2 white--text"
                        :href="`/isyeriegitimi/bitis-anketi/firma/` + item.id"
                        tile
                        >
                        <v-icon dark>mdi-file-document-edit-outline</v-icon>
                        {{ item.yetkili_anket_id?'Anketi Dolduruldu':'Anketi Doldur' }}
                        </v-btn>
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
            Items: [],

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
                api.get('/isyeriegitimi/bitis-anketi/ogrenci-listesi')
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
        },
    }
</script>
