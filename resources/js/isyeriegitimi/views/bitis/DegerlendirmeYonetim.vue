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
                    <v-col cols="12" sm="6" md="6">
                        <v-select
                            v-model="searchMulakatDurum"
                            :items="mulakatDurumList"
                            label="Mülakat Durumuna Göre Filtrele"
                            outlined
                            hide-details
                        >
                        </v-select>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                        <v-select
                            v-model="searchKabulDurum"
                            :items="kabulDurumList"
                            item-text="text"
                            item-value="value"
                            label="Onay Durumuna Göre Filtrele"
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
                    <template v-slot:top>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>İŞYERİ EĞİTİMİ MÜLAKAT NOTLARININ GİRİŞİ</v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
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
                                                    <v-col cols="12" sm="4" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.staj_gun_sayisi"
                                                            label="İşyeri Eğitimi Gün Sayısı"
                                                            outlined
                                                            :disabled=true
                                                            >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="4" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.kabul_edilen_gun_sayisi"
                                                            label="Kabul Edilen Gün Sayısı"
                                                            required
                                                            type="number"
                                                            outlined
                                                            :rules="[
                                                                () => !!editedItem.kabul_edilen_gun_sayisi || 'Bu alan gereklidir',
                                                                () => !!editedItem.kabul_edilen_gun_sayisi && editedItem.kabul_edilen_gun_sayisi <= editedItem.staj_gun_sayisi || 'Kabul Edilen Gün Sayısı Çalışılan Gün Sayısından Büyük Olamaz',
                                                            ]"
                                                            >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="4" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.red_edilen_gun_sayisi"
                                                            label="Red Edilen Gün Sayısı"
                                                            required
                                                            type="number"
                                                            outlined
                                                            :disabled=true
                                                            >
                                                        </v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.sicil_fisi_notu"
                                                            label="Sicil Fişi Notu"
                                                            required
                                                            type="number"
                                                            :rules="integerRules"
                                                            outlined
                                                            >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.dosya_notu"
                                                            label="Dosya Notu"
                                                            required
                                                            type="number"
                                                            :rules="integerRules"
                                                            outlined
                                                            >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.mulakat_notu"
                                                            label="Mülakat Notu"
                                                            required
                                                            type="number"
                                                            :rules="integerRules"
                                                            outlined
                                                            >
                                                        </v-text-field>
                                                    </v-col>

                                                </v-row>
                                            </v-container>
                                        </v-form>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" text @click="close">İptal</v-btn>
                                        <v-btn color="blue darken-1" text @click="save">Kaydet</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>

                        </v-toolbar>
                    </template>
                    <template v-slot:item.statu="{ item }">
                        <v-checkbox
                            v-model="item.statu"
                            :disabled="true"
                            >
                        </v-checkbox>
                    </template>

                    <template v-slot:item.action="{ item }">
                        <v-btn
                        small
                        color="teal"
                        class="ma-2 white--text"
                        fab
                        @click="editItem(item)"
                        >
                        <v-icon dark>mdi-pencil</v-icon>
                        </v-btn>
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
            mulakatDurumList: [
                'hepsi', 'bekleniyor', 'tarihi geçti', 'tamamlandı'
            ],
            searchMulakatDurum: 'hepsi',
            kabulDurumList: [
                {'text' : 'hepsi', value: 2},
                {'text' : 'onaylandı', value: 1},
                {'text' : 'onaylanmadı', value: 0}
            ],
            searchKabulDurum: 2,
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
            editedIndex: -1,
            editedItem: {
                id: 0,
                sicil_fisi_notu: 0,
                dosya_notu: 0,
                mulakat_notu: 0,
                staj_gun_sayisi: 0,
                kabul_edilen_gun_sayisi: 0,
                red_edilen_gun_sayisi: 0,
                statu: false,
            },
            defaultItem: {
                id: 0,
                sicil_fisi_notu: 0,
                dosya_notu: 0,
                mulakat_notu: 0,
                staj_gun_sayisi: 0,
                kabul_edilen_gun_sayisi: 0,
                red_edilen_gun_sayisi: 0,
                statu: false,
            },
            file: ''
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'Yeni Kayıt' : 'Kayıt Düzenle'
            },
            headers() {
                return [
                    { text: 'NO', value: 'user.ogrenci.ogrenci_no' },
                    { text: 'AD-SOYAD', value: 'user.name' },
                    {
                        text: 'MÜLAKAT DURUMU',
                        value: 'mulakat',
                        filter: value => {
                            if (this.searchMulakatDurum==='hepsi') return true;
                            return value === this.searchMulakatDurum
                        },
                    },
                    { text: 'ÇALIŞILAN GÜN SAYISI', value: 'staj_gun_sayisi'},
                    { text: 'SİCİL FİŞİ NOTU', value: 'sicil_fisi_notu' },
                    { text: 'DOSYA NOTU', value: 'dosya_notu' },
                    { text: 'MÜLAKAT NOTU', value: 'mulakat_notu'},
                    { text: 'KABUL EDİLEN GÜN', value: 'kabul_edilen_gun_sayisi'},
                    { text: 'RED EDİLEN GÜN', value: 'red_edilen_gun_sayisi'},
                    {
                        text: 'KABUL DURUMU',
                        value: 'statu',
                        filter: value => {
                            if (this.searchKabulDurum===2) return true;
                            return value === this.searchKabulDurum
                        },
                    },
                    { text: 'İŞLEM', value: 'action', sortable: false },
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
            "editedItem.kabul_edilen_gun_sayisi": function(newVal, previousVal) {
                console.log(newVal);
                if (this.editedItem.staj_gun_sayisi>=newVal)
                    this.editedItem.red_edilen_gun_sayisi = this.editedItem.staj_gun_sayisi - newVal;
            },
        },

        created () {
            this.getDegerlendirmeler();
        },

        methods: {
            getDegerlendirmeler () {
                api.get('/isyeriegitimi/degerlendirme/yonetim')
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

                let index = this.editedIndex;

                api.put('/isyeriegitimi/degerlendirme/yonetim/' + this.editedItem.id, JSON.stringify(this.editedItem))
                    .then(response => {
                        if(response.data.success) {
                            let item = this.editedItem;
                            Object.assign(this.Items[index], response.data.data);
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarılı';
                            this.$refs.dialogform.resetValidation();
                            this.close()
                        }
                    })
                    .catch(error => {
                        this.snackbar = true;
                        this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                    })


            },

        },
    }
</script>
