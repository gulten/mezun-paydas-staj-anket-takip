<template>
    <div>
        <v-snackbar top :color="color" :timeout="timeout" v-model="snackbar">
            {{ snackbarMessage }}
        </v-snackbar>
        <div class="container">
              <v-card flat>
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
                    :loading="loading" loading-text="İşlem Yapılıyor, Lütfen Bekleyin"
                >
                    <template v-slot:top>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>BÖLÜM BAŞKANI YARDIMCILARI</v-toolbar-title>
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
                                                        v-model="editedItem.name"
                                                        label="Kullanıcı Adı"
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
                                                            label="Bölüm Baş. Yard. Telefon"
                                                            :rules="phoneRules"
                                                            v-mask="'#(###) ### ## ##'"
                                                            required>
                                                        </v-text-field>
                                                    </v-col>
                                                    <template v-if="editedIndex===-1">
                                                        <v-col cols="12" sm="6" md="4">
                                                            <v-text-field
                                                                v-model="editedItem.password"
                                                                label="Şifre"
                                                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                                                :type="show ? 'text' : 'password'"
                                                                :rules="[ v => !!v || 'şifre alanı gereklidir',
                                                                                editedItem.password.length >= 8 || 'Şifre çok kısa']"
                                                                @click:append="show = !show"
                                                                required>
                                                            </v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="6" md="4">
                                                            <v-text-field
                                                                v-model="editedItem.password_confirmation"
                                                                label="Şifre Tekrar"
                                                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                                                :type="show ? 'text' : 'password'"
                                                                :rules="[ v => !!v || 'şifre alanı gereklidir',
                                                                                editedItem.password_confirmation.length >= 8 || 'Şifre çok kısa']"
                                                                @click:append="show = !show"
                                                                required>
                                                            </v-text-field>
                                                        </v-col>
                                                    </template>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-switch
                                                            label="Statü"
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
                            :loading="item.loadingmail"
                            :disabled="item.loadingmail"
                            color="indigo"
                            class="ma-2 white--text"
                            fab
                            @click="sendMail(item)"
                            >
                                <v-icon dark>mdi-email-send-outline</v-icon>
                            </v-btn>

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
                            color="pink"
                            class="ma-2 white--text"
                            fab
                            @click="(passwordModal = true),(EditId=item.id)"
                            >
                            <v-icon dark>
                                mdi-account-key
                            </v-icon>
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
        <v-layout justify-center>
            <v-dialog persistent no-click-animation v-model="passwordModal" max-width="850" width="650" min-height="400.25">
                <v-card>
                    <v-form v-model="validForm" lazy-validation ref="form">
                        <v-tabs
                            v-model="active"
                            color="cyan"
                            dark
                            centered
                            fixed-tabs
                            grow
                            slider-color="yellow">
                            <v-tab ripple>
                                Parolayı Değiştir
                            </v-tab>
                            <v-tab-item>
                                <v-card flat>
                                    <v-card-text>
                                        <v-flex xs12>
                                            <v-text-field
                                                label="Yeni Şifre"
                                                v-model="newPassword"
                                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                                :type="show ? 'text' : 'password'"
                                                :rules="[ v => !!v || 'şifre gereklidir',
                                                                this.newPassword.length >= 8 || 'şifre çok kısa',
                                                                this.newPassword === this.passwordConfirmation || 'şifreler uyuşmuyor']"
                                                @click:append="show = !show"
                                                required>
                                            </v-text-field>
                                            <v-text-field
                                                label="Şifre Tekrar"
                                                v-model="passwordConfirmation"
                                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                                :type="show ? 'text' : 'password'"
                                                :rules="[ v => this.newPassword === this.passwordConfirmation || 'şifreler uyuşmuyor']"
                                                @click:append="show = !show"
                                                required>
                                            </v-text-field>

                                        </v-flex>

                                    </v-card-text>
                                </v-card>
                            </v-tab-item>
                        </v-tabs>
                    </v-form>
                    <v-card-actions>
                        <v-btn

                            color="error"
                            @click="closeModal()">
                            Kapat
                        </v-btn>
                        <v-btn
                            :loading="buttonLoader"
                            :disabled="buttonLoader"
                            color="info"
                            @click="changePassword()">
                            Kaydet
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>
<script>
    export default {
        components:{
        },
        data: () => ({
            search: '',
            dialog: false,
            show: false,
            active: null,
            newPassword: '',
            passwordConfirmation: '',
            buttonLoader: false,
            validForm: true,
            dialogValid: true,
            passwordModal: false,
            selectUser: '',
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
                { text: 'AD', value: 'name' },
                { text: 'E-MAIL', value: 'email' },
                { text: 'BÖLÜM BŞK. YRD. TELEFON', value: 'telefon' },
                { text: 'STATÜ', value: 'status' },
                { text: 'İŞLEM', value: 'action', sortable: false },
            ],
            Items: [],
            editedIndex: -1,
            editedItem: {
                id: 0,
                name: '',
                email: '',
                telefon: '',
                status: 'active',
                password: '',
                password_confirmation: '',
            },
            defaultItem: {
                id: 0,
                name: '',
                email: '',
                telefon: '',
                status: 'active',
                password: '',
                password_confirmation: '',
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
                api.get('/bbys/bolum-baskani-yardimcilari')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.bolum_baskani_yardimcilari;
                        }
                    })
            },
            editItem (item) {
                this.editedIndex = this.Items.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            deleteItem (item) {
                if (confirm('Bu kullanıcıyı silmek istediğinize emin misiniz? Eğer silerseniz bütün rolleri silinecektir.')){
                    this.loader = true;
                    let index = this.Items.indexOf(item);

                    api.delete('/bbys/bolum-baskani-yardimcilari/' + item.id).then(response => {
                        if (response.data.success) {
                            setTimeout(() => {
                                this.loader = false;
                                this.Items.splice(index, 1);
                                this.color = 'success';
                                this.snackbar = true;
                                this.snackbarMessage = "Kullanıcı Başarıyla Silindi";
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
                if (this.editedIndex > -1) {
                    let item = this.editedItem;
                    let index = this.editedIndex;
                    api.put('/bbys/bolum-baskani-yardimcilari/' + this.editedItem.id, JSON.stringify(this.editedItem))
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
                    api.post('/bbys/bolum-baskani-yardimcilari/', JSON.stringify(this.editedItem))
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
            changePassword(){

                if (this.$refs.form.validate()) {

                    this.buttonLoader = true;
                    let formData = new FormData();
                    formData.append('password', this.newPassword);
                    formData.append('password_confirmation', this.passwordConfirmation);
                    formData.append('_method', 'PUT');
                    api.post('/bbys/kullanicilar/' + this.EditId + '/change-password', formData).then(response => {

                        if (response.data.success) {
                            this.color = 'success';
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarılı';
                            this.closeModal()
                        }
                    }).catch(error => {
                        this.color = 'error';
                        this.snackbar = true;
                        this.snackbarMessage = 'Ekleme İşlemi Başarısız';
                        this.buttonLoader = false;
                    })
                }
                else {
                    this.color = 'error';
                    this.snackbarMessage = 'İşlem Başarısız';
                    this.buttonLoader = false;
                }

            },
            sendMail(item) {
                let id = item.id;
                this.loading = true;
                item.loadingmail = true;
                api.get('/bbys/send_mail/' + id)
                    .then(response => {
                        if (response.data.success) {
                            this.color = 'success';
                            this.snackbar = true;
                            this.snackbarMessage = 'Kullanıcıya Bilgilendirme Maili Gönderildi';
                            this.loading = false;
                            item.loadingmail = false;
                        }
                    })
                    .catch(error => {
                        this.color = 'error';
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                            this.loading = false;
                            item.loadingmail = false;
                        })
            },
            enableDisableUser(id){
                console.log('toggle');
                api.put('/bbys/kullanicilar/' + id + '/toggle-status').then(response => {
                    this.color = 'success';
                    this.snackbar = true;
                    this.snackbarMessage = 'İşlem Başarılı';
                })
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
