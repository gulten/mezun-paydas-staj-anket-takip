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
                            <v-toolbar-title>BİTİŞ BELGELERİNİ DÜZENLE</v-toolbar-title>
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
                                                        v-model="editedItem.belge_adi"
                                                        label="Belge Adı"
                                                        :rules="stringRules"
                                                        >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <input
                                                            type="file"
                                                            label="Belge Yolu"
                                                            @change="fileUpload"
                                                            ref="myFileInput"
                                                            >
                                                    </v-col>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.belge_aciklama"
                                                            label="Belge Açıklama"
                                                            required
                                                        >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-switch
                                                            label="Gereklilik"
                                                            v-model="editedItem.status"
                                                            false-value="passive"
                                                            true-value="active"
                                                        >
                                                        </v-switch>
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
                    <template v-slot:item.belge_yolu="{ item }">
                         <v-btn
                            icon
                            @click="fileDownload(item.belge_yolu)"
                        >
                        <v-icon dark>mdi-file-download</v-icon>
                        </v-btn>
                    </template>

                    <template v-slot:item.status="{ item }">
                        <v-switch
                            v-model="item.status"
                            false-value="passive"
                            true-value="active"
                            @change="enableDisableUser(item.id)">
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
                        <v-btn color="primary" @click="getDocuments">Reset</v-btn>
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
            headers: [
                { text: 'BELGE ADI', value: 'belge_adi' },
                { text: 'BELGE', value: 'belge_yolu' },
                { text: 'BELGE AÇIKLAMASI', value: 'belge_aciklama' },
                { text: 'GEREKLİLİK', value: 'status' },
                { text: 'İŞLEM', value: 'action', sortable: false },
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
            file: ''
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
            this.getDocuments();
        },

        methods: {
            getDocuments () {
                api.get('/isyeriegitimi/bitis-belgeleri/duzenle')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.belgeler;
                        }
                    });
            },
            editItem (item) {
                this.editedIndex = this.Items.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            deleteItem (item) {
                if (confirm('Bu belgeyi silmek istediğinize emin misiniz?')){
                    this.loader = true;
                    let index = this.Items.indexOf(item);

                    api.delete('/isyeriegitimi/bitis-belgeleri/duzenle/' + item.id).then(response => {
                        if (response.data.success) {
                            setTimeout(() => {
                                this.loader = false;
                                this.Items.splice(index, 1);
                                this.color = 'success';
                                this.snackbar = true;
                                this.snackbarMessage = "Belge Başarıyla Silindi";
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
            onUpload() {
            },
            save () {
                if (!this.$refs.dialogform.validate()) {
                    this.color = 'error';
                    this.snackbar = true;
                    this.snackbarMessage = "İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz";
                    return false;
                }
                const config = {
                    headers: {
                        'Content-Type' : 'multipart/form-data'
                    }
                };

                let formData = new FormData();
                    if (typeof this.editedItem.belge_yolu === "string" )
                        this.editedItem.belge_yolu = null;

                    formData.append("belge_yolu", this.editedItem.belge_yolu);
                    formData.append("belge_adi", this.editedItem.belge_adi);
                    formData.append("belge_aciklama", this.editedItem.belge_aciklama);
                    formData.append("status", this.editedItem.status);

                if (this.editedIndex > -1) {
                    let index = this.editedIndex;
                    let item = this.editedItem;

                    formData.append("_method", "PUT");
                    api.post('/isyeriegitimi/bitis-belgeleri/duzenle/' + this.editedItem.id, formData, config)
                        .then(response => {
                            if(response.data.success) {
                                item = response.data.data;
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

                } else {
                    let item = this.editedItem;
                    api.post('/isyeriegitimi/bitis-belgeleri/duzenle',  formData, config)
                        .then(response => {
                            if(response.data.success) {
                                item = response.data.data;
                                this.Items.push(item);
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
                }
            },
            enableDisableUser(id){
                api.put('/isyeriegitimi/bitis-belgeleri/duzenle/' + id + '/toggle-status').then(response => {
                    this.color = 'success';
                    this.snackbar = true;
                    this.snackbarMessage = 'İşlem Başarılı';
                })
            },
            ReadFile(e){
                let reader = new FileReader();
                reader.readAsDataURL(this.editedItem.belge_yolu);
                reader.onload = e => {
                    console.log(e);
                }
            },
            fileUpload(e){
                this.editedItem.belge_yolu  = e.target.files[0];
            },
            fileDownload(url){
                window.open(url, '_blank');
            }

        },
    }
</script>
