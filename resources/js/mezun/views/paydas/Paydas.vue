<template>
    <div>
        <div class="container">
            <v-card
                class="overflow-hidden"
                color="lighten-1"
            >
                <v-toolbar
                    flat
                >
                    <v-icon>mdi-account</v-icon>
                    <v-toolbar-title class="font-weight-light">Paydas Profili</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="darken-3"
                        class="mr-4"
                        fab
                        small
                        @click="passwordModal = true"
                    >
                        <v-icon>mdi-key</v-icon>
                    </v-btn>
                    <v-btn
                        color="darken-3"
                        fab
                        small
                        @click="isEditing = !isEditing"
                    >
                        <v-icon v-if="isEditing">mdi-close</v-icon>
                        <v-icon v-else>mdi-pencil</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-form v-model="dialogValid" ref="dialogform">
                    <v-card-text>
                        <v-text-field
                            :disabled="!isEditing"
                            ref="name"
                            v-model="name"
                            label="Ad"
                            :rules="stringRules"
                            >
                        </v-text-field>
                        <v-text-field
                            :disabled="!isEditing"
                            ref="mail"
                            v-model="email"
                            label="E-mail"
                            :rules="emailRules"
                            >
                        </v-text-field>
                        <v-text-field
                            :disabled="!isEditing"
                            ref="telefon"
                            v-model="telefon"
                            label="Telefon"
                            :rules="phoneRules"
                            v-mask="'#(###) ### ## ##'"
                        ></v-text-field>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            :disabled="!isEditing"
                            color="success"
                            @click="save"
                        >
                            Kaydet
                        </v-btn>
                    </v-card-actions>
                    <v-snackbar
                        v-model="hasSaved"
                        :timeout="2000"
                        absolute
                        bottom
                        left
                    >
                        {{ snackbarMessage }}
                    </v-snackbar>
                    </v-card-text>
                </v-form>
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
                                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                                :type="show ? 'text' : 'password'"
                                                :rules="[ v => !!v || 'field is required',
                                                                this.newPassword.length >= 8 || 'Password is too short',
                                                                this.newPassword === this.passwordConfirmation || 'Password must match']"
                                                @click:append="show = !show"
                                                required>
                                            </v-text-field>
                                            <v-text-field
                                                label="Password Confirmation"
                                                v-model="passwordConfirmation"
                                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
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
            valid: true,
            editedIndex: -1,
            show: false,
            menu: false,
            active: null,
            newPassword: '',
            passwordConfirmation: '',
            buttonLoader: false,
            validForm: true,
            passwordModal: false,
            id: 0,
            user_id: 0,
            name: '',
            email: '',
            telefon: '',
            password: '',
            password_confirmation: '',
            modal: false,
            snackbarMessage: '',
            dialogValid: true,
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
            hasSaved: false,
            isEditing: null,
            model: null,

        }),
        mounted(){
            this.getData();
        },

        methods: {
            getData () {
                api.get('mezun/paydas-profil')
                    .then(response => {
                        if(response.data.success) {
                            this.name = response.data.data.name;
                            this.email = response.data.data.email;
                            this.telefon = response.data.data.telefon;
                        }
                    })
            },
            save () {
                if (!this.$refs.dialogform.validate()) {
                    this.snackbar = true;
                    this.snackbarMessage = "İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz";
                    return false;
                }

                let config = {
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    };
                let requestBody = {
                            name: this.name,
                            email: this.email,
                            telefon: this.telefon,
                        };

                api.post('mezun/paydas-profil', JSON.stringify(requestBody), config)
                    .then(response => {
                        if (response.data.success){
                            this.hasSaved = true;
                            this.snackbarMessage = 'İşlemi Başarılı';
                            this.isEditing = !this.isEditing;
                        }

                    })
                    .catch(error => {
                        this.snackbar = true;
                        this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                    })
            },
            changePassword(){

                if (this.$refs.form.validate()) {

                    this.buttonLoader = true;
                    let formData = new FormData();
                    formData.append('password', this.newPassword);
                    formData.append('password_confirmation', this.passwordConfirmation);
                    formData.append('_method', 'PUT');
                    api.post('mezun/paydas-profil/change-password', formData).then(response => {

                        if (response.data.success) {
                            this.hasSaved = true;
                            this.snackbarMessage = 'İşlemi Başarılı';
                            this.closeModal()
                        } else {
                            this.hasSaved = true;
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
                    this.hasSaved = true;
                    this.snackbarMessage = 'İşlemi Başarısız';
                    this.buttonLoader = false;
                }

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
