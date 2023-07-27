<template>
    <div>
        <v-snackbar top :color="color" :timeout="timeout" v-model="snackbar">
            {{ snackbarMessage }}
        </v-snackbar>
        <div class="container">
            <v-card>
                <v-card-title class="elevation-1">
                    FİLTRELER
                    <v-spacer>
                    </v-spacer>
                </v-card-title>
                <v-card-text>
                <v-row>
                    <v-col cols="12" sm="6" md="6">
                        <v-select
                            v-model="searchIslemDurum"
                            :items="islemDurumList"
                            label="İşyeri Eğitimi Tamamlanma Durumuna Göre Filtrele"
                            outlined
                            hide-details
                        >
                        </v-select>
                    </v-col>
                </v-row>
                </v-card-text>
            </v-card>
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
                    :loading="loading" loading-text="İşlem Yapılıyor, Lütfen Bekleyin"
                >
                    <template v-slot:item.statu="{ item }">
                        <v-checkbox
                            v-model="item.statu"
                            :disabled="true"
                            >
                        </v-checkbox>
                    </template>

                    <template v-slot:item.tamamlanan.statu="{ item }">
                        <v-btn
                        v-if="item.tamamlanan.statu==='tamamlandı'"
                            large
                            class="ma-2"
                            text
                            icon
                            color="green lighten-2"
                        >
                            <v-icon>mdi-check</v-icon>
                        </v-btn>
                    </template>
                    <template v-slot:no-data>
                        <v-btn color="primary" @click="getDegerlendirmeler">Reset</v-btn>
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
            islemDurumList: [
                'hepsi', 'tamamlandı', 'tamamlanmadı',
            ],
            searchIslemDurum: 'hepsi',
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
            integerRules: [
            v => !!v || 'Bu alan gereklidir',
            ],
            emailRules: [
                v => !!v || 'E-mail alanı gereklidir',
                v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail formatı yanlış',
            ],
            Items: [],
        }),

        computed: {
            headers() {
                return [
                    { text: 'NO', value: 'user.ogrenci.ogrenci_no' },
                    { text: 'AD-SOYAD', value: 'user.name' },
                    { text: 'KABUL EDİLEN GÜN', value: 'toplam_gun'},
                    {
                        text: 'BİTİŞ DURUM',
                        value: 'tamamlanan.statu',
                        filter: value => {
                            if (this.searchIslemDurum==='hepsi') return true;
                            return value === this.searchIslemDurum
                        },
                    },
                ]
            }
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
        },

        created () {
            this.getDegerlendirmeler();
        },

        methods: {
            getDegerlendirmeler () {
                api.get('/isyeriegitimi/durum/liste')
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
