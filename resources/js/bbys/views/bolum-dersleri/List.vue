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
                            <v-toolbar-title>BÖLÜM DERSLERİ</v-toolbar-title>
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
                                                    <v-col cols="12" sm="4">
                                                        <v-select
                                                            label="Sınıf"
                                                            v-model="editedItem.sinif"
                                                            :items="siniflar"
                                                            item-text="value"
                                                            :rules="stringRules"
                                                            outlined
                                                        ></v-select>
                                                    </v-col>
                                                    <v-col cols="12" sm="4">
                                                        <v-select
                                                            label="Dönem"
                                                            v-model="editedItem.donem"
                                                            :items="donemler"
                                                            item-text="value"
                                                            :rules="stringRules"
                                                            outlined
                                                        ></v-select>
                                                    </v-col>
                                                    <v-col cols="12" sm="4">
                                                        <v-select
                                                            label="Durum"
                                                            v-model="editedItem.durum"
                                                            :items="durumlar"
                                                            item-text="value"
                                                            :rules="stringRules"
                                                            outlined
                                                        ></v-select>
                                                    </v-col>
                                                    <v-col cols="12" sm="4">
                                                        <v-text-field
                                                        v-model="editedItem.ders_kodu"
                                                        label="Ders Kodu"
                                                        :rules="stringRules"
                                                        outlined
                                                        >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="4">
                                                        <v-text-field
                                                        v-model="editedItem.ders_adi"
                                                        label="Ders Adı"
                                                        :rules="stringRules"
                                                        outlined
                                                        >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="4">
                                                        <v-text-field
                                                            v-model="editedItem.haftalik_ders_saati"
                                                            label="Hastalık Ders Saati"
                                                            required
                                                            outlined
                                                            >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="12">
                                                        <v-textarea
                                                            v-model="editedItem.amac"
                                                            label="Amaç"
                                                            required
                                                            outlined
                                                            >
                                                        </v-textarea>
                                                    </v-col>
                                                    <v-col cols="12" sm="12">
                                                        <v-textarea
                                                            v-model="editedItem.icerik"
                                                            label="İçerik"
                                                            required
                                                            outlined
                                                            >
                                                        </v-textarea>
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
                    <template v-slot:item.status="{ item }">
                        <v-switch
                            v-model="item.status"
                            false-value="passive"
                            true-value="active">
                        </v-switch>
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
                    <template v-slot:no-data>
                        <v-btn color="primary" @click="getUsers">Reset</v-btn>
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
            dialog: false,
            dialogValid: true,
            show: false,
            color: 'success',
            snackbar: false,
            snackbarMessage: '',
            timeout: 2000,
            stringRules: [
            v => !!v || 'Bu alan gereklidir',
            ],
            headers: [
                { text: 'SINIF', value: 'sinif' },
                { text: 'DONEM', value: 'donem' },
                { text: 'DURUM', value: 'durum' },
                { text: 'DERS KODU', value: 'ders_kodu' },
                { text: 'DERS ADI', value: 'ders_adi' },
                { text: 'HAFTALIK DERS SAATİ (Teorik + Uygulama/Lab)', value: 'haftalik_ders_saati', align: 'center', width: '15%' },
                { text: 'AMAÇ', value: 'amac' },
                { text: 'İÇERİK', value: 'icerik' },
                { text: 'İŞLEM', value: 'action', sortable: false },
            ],
            Items: [],
            editedIndex: -1,
            siniflar: [
                {value: 'Hazırlık'},
                {value: '1'},
                {value: '2'},
                {value: '3'},
                {value: '4'}
            ],
            donemler: [
                {value: 'Güz'},
                {value: 'Bahar'}
            ],
            durumlar: [
                {value: 'Zorunlu'},
                {value: 'Seçmeli'}
            ],
            editedItem: {
                id: 0,
                sinif: '',
                donem: '',
                durum: '',
                ders_kodu: '',
                ders_adi: '',
                haftalik_ders_saati: '',
                amac: '',
                icerik: '',
            },
            defaultItem: {
                id: 0,
                sinif: '',
                donem: '',
                durum: '',
                ders_kodu: '',
                ders_adi: '',
                haftalik_ders_saati: '',
                amac: '',
                icerik: '',
            },
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
            this.getUsers();
        },

        methods: {
            getUsers () {
                api.get('/bbys/bolum-dersleri')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.bolum_dersleri;
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

                    api.delete('/bbys/bolum-dersleri/' + item.id).then(response => {
                        if (response.data.success) {
                            setTimeout(() => {
                                this.loader = false;
                                this.Items.splice(index, 1);
                                this.snackbar = true;
                                this.snackbarMessage = "Ders Başarıyla Silindi";
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
                let item = this.editedItem;
                if (this.editedIndex > -1) {
                    let index = this.editedIndex;
                    api.put('/bbys/bolum-dersleri/' + this.editedItem.id, JSON.stringify(this.editedItem))
                        .then(response => {
                            if(response.data.success) {
                                Object.assign(this.Items[index], item);
                                this.snackbar = true;
                                this.snackbarMessage = 'İşlem Başarılı';
                                this.$refs.dialogform.resetValidation();
                                this.close();
                            }
                        })
                        .catch(error => {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                        })

                } else {
                    api.post('/bbys/bolum-dersleri', JSON.stringify(this.editedItem))
                        .then(response => {
                            if(response.data.success) {
                                item = response.data.data;
                                this.Items.push(item);
                                this.snackbar = true;
                                this.snackbarMessage = 'İşlem Başarılı';
                                this.$refs.dialogform.resetValidation();
                                this.close();
                            }
                        })
                        .catch(error => {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                        })

                }
            },
        },
    }
</script>
