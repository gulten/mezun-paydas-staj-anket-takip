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
                    :loading="loading" loading-text="İşlem Yapılıyor, Lütfen Bekleyin"
                >
                    <template v-slot:top>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>FİRMA BİLGİLERİ</v-toolbar-title>
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
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field
                                                        v-model="editedItem.adi"
                                                        label="Firma Adı"
                                                        :rules="stringRules"
                                                        >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.email"
                                                            label="E-mail"
                                                            required
                                                            :rules="emailRules"
                                                        >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.telefon"
                                                            label="Telefon"
                                                            :rules="phoneRules"
                                                            v-mask="'#(###) ### ## ##'"
                                                            required
                                                        >
                                                        </v-text-field>
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
                        <v-btn color="primary" @click="getList">Reset</v-btn>
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
            active: null,
            rowsPerPage: 25,
            dialog: false,
            show: false,
            selectUser: '',
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
            phoneRules: [
                v => (/^(\s)?\d{1}(\()\d{3}(\))(\s)\d{3}(\s)\d{2}(\s)\d{2}$/.test(v)||!v) || 'Telefon formatı hatalıdır',
            ],
            headers: [
                { text: 'AD', value: 'adi' },
                { text: 'E-MAIL', value: 'email' },
                { text: 'TELEFON', value: 'telefon' },
                { text: 'İŞLEM', value: 'action', sortable: false },
            ],
            Items: [],
            editedIndex: -1,
            editedItem: {
                id: 0,
                adi: '',
                email: '',
                telefon: '',
            },
            defaultItem: {
                id: 0,
                adi: '',
                email: '',
                telefon: '',
            },
            isLoading: false,
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'Yeni Kayıt' : 'Kayıt Düzenle'
            },
        },

        watch: {
            dialog (val) {
                val || this.close()
            }
        },
        created () {
            this.getList();
        },

        methods: {
            getList () {
                api.get('/bbys/firma')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.firmalar;
                        }
                    })
                    .catch(error => console.log(error))
            },
            editItem (item) {
                this.editedIndex = this.Items.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            deleteItem (item) {
                if (confirm('Bu firmayı silmek istediğinize emin misiniz?')){
                    this.loader = true;
                    let index = this.Items.indexOf(item);

                    api.delete('/bbys/firma/' + item.id)
                        .then(response => {
                            if (response.data.success) {
                                setTimeout(() => {
                                    this.loader = false;
                                    this.Items.splice(index, 1);
                                    this.color = 'success';
                                    this.snackbar = true;
                                    this.snackbarMessage = "Firma Başarıyla Silindi";
                                }, 1000);
                            }
                        })
                        .catch(error => {
                            this.color = 'error';
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız.';
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
                if (this.editedIndex > -1) {
                    let item = this.editedItem;
                    let index = this.editedIndex;
                    api.put('/bbys/firma/' + this.editedItem.id, JSON.stringify(this.editedItem))
                        .then(response => {
                            if(response.data.success) {
                                Object.assign(this.Items[index], item);
                                this.color = 'success';
                                this.snackbar = true;
                                this.snackbarMessage = 'İşlem Başarılı';
                                this.$refs.dialogform.resetValidation();
                                this.close();
                            }
                        })
                        .catch(error => {
                            this.color = 'error';
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                        })

                } else {
                    let item = this.editedItem;
                    api.post('/bbys/firma', JSON.stringify(this.editedItem))
                        .then(response => {
                            if(response.data.success) {
                                item = response.data.data;
                                this.Items.push(item);
                                this.color = 'success';
                                this.snackbar = true;
                                this.snackbarMessage = 'İşlem Başarılı';
                                this.$refs.dialogform.resetValidation();
                                this.close();
                            }
                        })
                        .catch(error => {
                            this.color = 'error';
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                        })
                }
            },

            closeModal(){
                this.$refs.form.resetValidation();
                this.buttonLoader = false;
                this.active = 0;
            },
        },
    }
</script>
