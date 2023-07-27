<template>
    <div>
        <v-snackbar top :timeout="timeout" v-model="snackbar">
            {{ snackbarMessage }}
        </v-snackbar>
        <div class="container">
            <v-card>
                <v-card-title>
                    <v-text-field
                        v-model="search"
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details>
                    </v-text-field>
                </v-card-title>
                <v-data-table
                    :headers="headers"
                    :items="Items"
                    class="elevation-1"
                    sort-by="name"
                    :search="search"
                    :items-per-page="rowsPerPage"
                >
                    <template v-slot:item.roles="{ item }">
                        <template v-for="role in item.roles">
                            <template v-if="allRoles.find(element => element.name === role.name)">
                                <v-chip dark class="ma-2">{{ role.name }}</v-chip>
                            </template>
                        </template>
                    </template>
                    <template v-slot:top>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>KOMİSYONLAR</v-toolbar-title>
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
                                                    <v-col cols="12">
                                                        <v-autocomplete
                                                            v-model="editedItem.roles"
                                                            :items="allRoles"
                                                            outlined
                                                            dense
                                                            chips
                                                            small-chips
                                                            label="Roller"
                                                            multiple
                                                            item-value="name"
                                                            item-text="name"
                                                            return-object
                                                        ></v-autocomplete>
                                                    </v-col>
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
                                                        <v-switch
                                                            label="Statü"
                                                            v-model="editedItem.status"
                                                            false-value="passive"
                                                            true-value="active"
                                                        >
                                                        </v-switch>
                                                    </v-col>
                                                    <template v-if="editedIndex===-1">
                                                        <v-col cols="12" sm="6" md="4">
                                                            <v-text-field
                                                                v-model="editedItem.password"
                                                                label="Şifre"
                                                                :append-icon="show ? 'visibility' : 'visibility_off'"
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
                                                                :append-icon="show ? 'visibility' : 'visibility_off'"
                                                                :type="show ? 'text' : 'password'"
                                                                :rules="[ v => !!v || 'şifre alanı gereklidir',
                                                                            editedItem.password_confirmation.length >= 8 || 'Şifre çok kısa']"
                                                                @click:append="show = !show"
                                                                required>
                                                            </v-text-field>
                                                        </v-col>
                                                    </template>
                                                </v-row>
                                            </v-container>
                                        </v-form>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                                        <v-btn color="blue darken-1" text @click="save">Save</v-btn>
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
                        <v-icon
                            small
                            class="mr-2"
                            @click="sendMail(item.id)"
                        >
                            mdi-email-send-outline
                        </v-icon>
                        <v-icon
                            small
                            class="mr-2"
                            @click="editItem(item)"
                        >
                            edit
                        </v-icon>
                        <v-icon slot="activator" class="zmdi zmdi-key" small
                                @click="(passwordModal = true),(EditId=item.id)">
                        </v-icon>
                        <v-icon
                            small
                            @click="deleteItem(item)"
                        >
                            delete
                        </v-icon>
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
                                Change Password
                            </v-tab>
                            <v-tab-item>
                                <v-card flat>
                                    <v-card-text>
                                        <v-flex xs12>
                                            <v-text-field
                                                label="New Password"
                                                v-model="newPassword"
                                                :append-icon="show ? 'visibility' : 'visibility_off'"
                                                :type="show ? 'text' : 'password'"
                                                :rules="[ v => !!v || 'branch is required',
                                                                this.newPassword.length >= 8 || 'Password is too short',
                                                                this.newPassword === this.passwordConfirmation || 'Password must match']"
                                                @click:append="show = !show"
                                                required>
                                            </v-text-field>
                                            <v-text-field
                                                label="Password Confirmation"
                                                v-model="passwordConfirmation"
                                                :append-icon="show ? 'visibility' : 'visibility_off'"
                                                :type="show ? 'text' : 'password'"
                                                :rules="[ v => this.newPassword === this.passwordConfirmation || 'Password must match']"
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
                            Cancel
                        </v-btn>
                        <v-btn
                            :loading="buttonLoader"
                            :disabled="buttonLoader"
                            color="info"
                            @click="changePassword()">
                            Save
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
            rowsPerPage: 25,
            dialog: false,
            active: null,
            show: false,
            newPassword: '',
            passwordConfirmation: '',
            buttonLoader: false,
            validForm: true,
            dialogValid: true,
            passwordModal: false,
            selectUser: '',
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
                { text: 'KOMİSYON', value: 'roles' },
                { text: 'AD', value: 'name' },
                { text: 'E-MAIL', value: 'email' },
                { text: 'STATÜ', value: 'status' },
                { text: 'İŞLEM', value: 'action', sortable: false },
            ],
            Items: [],
            allRoles: [],
            editedIndex: -1,
            editedItem: {
                id: 0,
                role_id: 0,
                user_id: 0,
                name: '',
                email: '',
                roles: [],
                status: '',
                password: '',
                password_confirmation: '',
            },
            defaultItem: {
                id: 0,
                role_id: 0,
                user_id: 0,
                name: '',
                email: '',
                roles: [],
                status: '',
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
            this.getAllRoles();
            this.getUsers();
        },

        methods: {
            getAllRoles(){
                api.get('/bbys/komisyonlar/all_roles')
                    .then(response => {
                        if (response.data.success) {
                            this.allRoles = response.data.data.roller;
                        }
                    });
            },
            getUsers () {
                api.get('/bbys/komisyonlar')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.komisyonlar;
                        }
                    });
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

                    api.delete('/bbys/komisyonlar/' + item.id).then(response => {
                        if (response.data.success) {
                            setTimeout(() => {
                                this.loader = false;
                                this.Items.splice(index, 1);
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
                    this.snackbar = true;
                    this.snackbarMessage = "İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz";
                    return false;
                }
                if (this.editedIndex > -1) {
                    let item = this.editedItem;
                    let index = this.editedIndex;
                    api.put('/bbys/komisyonlar/' + this.editedItem.id, JSON.stringify(this.editedItem))
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
                    let item = this.editedItem;
                    api.post('/bbys/komisyonlar', JSON.stringify(this.editedItem))
                        .then(response => {
                            if(response.data.success) {
                                item.roles = response.data.data.roles;
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
            changePassword(){

                if (this.$refs.form.validate()) {

                    this.buttonLoader = true;
                    let formData = new FormData();
                    formData.append('password', this.newPassword);
                    formData.append('password_confirmation', this.passwordConfirmation);
                    formData.append('_method', 'PUT');
                    api.post('/bbys/kullanicilar/' + this.EditId + '/change-password', formData).then(response => {

                        if (response.data.success) {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlemi Başarılı';
                            this.closeModal()
                        } else {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlemi Başarısız';
                            this.buttonLoader = false;

                        }
                    }).catch(error => {
                        this.snackbar = true;
                        this.snackbarMessage = 'Ekleme İşlemi Başarısız';
                        this.buttonLoader = false;
                    })
                }
                else {

                    this.snackbarMessage = 'İşlemi Başarısız';
                    this.buttonLoader = false;
                }

            },
            enableDisableUser(id){
                api.put('/bbys/kullanicilar/' + id + '/toggle-status').then(response => {
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
