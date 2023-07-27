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
                    <v-toolbar-title class="font-weight-light">Bölüm Başkanı Profili</v-toolbar-title>
                    <v-spacer></v-spacer>
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
                <v-form
                    ref="form"
                    v-model="valid"
                >
                    <v-card-text>
                        <v-text-field
                            :disabled="!isEditing"
                            ref="name"
                            v-model="name"
                            label="Ad"
                        ></v-text-field>
                        <v-text-field
                            :disabled="!isEditing"
                            ref="mail"
                            v-model="email"
                            label="E-mail"
                        ></v-text-field>
                        <v-text-field
                            :disabled="!isEditing"
                            ref="telefon"
                            v-model="telefon"
                            label="Telefon"
                            :rules="phoneRules"
                            v-mask="'#(###) ### ## ##'"
                        ></v-text-field>
                        <v-text-field
                            :disabled="!isEditing"
                            ref="is_email"
                            v-model="is_email"
                            label="Bölüm Başkanlığı E-mail"
                        ></v-text-field>
                        <template v-if="editedIndex===0">
                            <v-text-field
                                :disabled="!isEditing"
                                v-model="password"
                                label="Şifre"
                                :append-icon="show ? 'visibility' : 'visibility_off'"
                                :type="show ? 'text' : 'password'"
                                :rules="[ v => !!v || 'şifre alanı gereklidir',
                                password.length >= 8 || 'Şifre çok kısa']"
                                @click:append="show = !show"
                                required>
                            </v-text-field>
                            <v-text-field
                                :disabled="!isEditing"
                                v-model="password_confirmation"
                                label="Şifre Tekrar"
                                :append-icon="show ? 'visibility' : 'visibility_off'"
                                :type="show ? 'text' : 'password'"
                                :rules="[ v => !!v || 'şifre alanı gereklidir',
                                password_confirmation.length >= 8 || 'Şifre çok kısa']"
                                @click:append="show = !show"
                                required>
                            </v-text-field>
                        </template>
                    </v-card-text>
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
                </v-form>
            </v-card>
        </div>
    </div>
</template>

<script>
    export default {
        components:{
        },
        data: () => ({
            valid: true,
            editedIndex: 1,
            show: false,
            id: 0,
            name: '',
            email: '',
            telefon: '',
            is_email: '',
            password: '',
            password_confirmation: '',
            modal: false,
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+/.test(v) || 'E-mail must be valid',
            ],
            phoneRules: [
                v => (/^(\s)?\d{1}(\()\d{3}(\))(\s)\d{3}(\s)\d{2}(\s)\d{2}$/.test(v)||v.length==0) || 'Telefon formatı hatalıdır',
            ],
            hasSaved: false,
            snackbarMessage: '',
            isEditing: null,
            model: null,

        }),
        mounted(){
            this.getData();
        },

        methods: {
            getData () {
                api.get('/bbys/bolum-baskani')
                    .then(response => {
                        if(response.data.data[0]) {
                            this.editedIndex = 1;
                            this.id = response.data.data[0].id;
                            this.name = response.data.data[0].name;
                            this.email = response.data.data[0].email;
                            this.telefon = response.data.data[0].bolumbaskani.telefon?response.data.data[0].bolumbaskani.telefon:"";
                            this.is_email = response.data.data[0].bolumbaskani.email?response.data.data[0].bolumbaskani.email:"";
                        }
                        else {
                            this.editedIndex = 0;
                        }
                    })
                    .catch(error => console.log(error))
            },
            save () {

                let formData = new FormData();

                formData.append('name', this.name);
                formData.append('email', this.email);
                formData.append('telefon', this.telefon);
                formData.append('is_email', this.is_email);

                if (this.editedIndex===1) {
                    formData.append("_method", "PUT");
                    api.post('/bbys/bolum-baskani/' + this.id, formData)
                        .then(response => {
                            this.isEditing = !this.isEditing;
                            this.hasSaved = true;
                            this.snackbarMessage = 'Bölüm Başkananı Bilgileri Güncellendi';
                        })
                        .catch(error => {
                            this.hasSaved = true;
                            this.snackbarMessage = 'İşlem Başarısız, Lütfen Alanları Kontrol Ediniz';
                            this.buttonLoader = false;
                        })
                }
                else if (this.editedIndex===0) {
                    formData.append('password', this.password);
                    formData.append('password_confirmation', this.password_confirmation);
                    api.post('/bbys/bolum-baskani', formData)
                        .then(response => {
                            this.isEditing = !this.isEditing;
                            this.hasSaved = true;
                            this.snackbarMessage = 'Bölüm Başkananı Bilgileri Eklendi';
                            this.getData();
                        })
                        .catch(error => {
                            this.hasSaved = true;
                            this.snackbarMessage = 'İşlem Başarısız, Lütfen Alanları Kontrol Ediniz';
                            this.buttonLoader = false;
                        })
                }
            },
        },
    }
</script>
