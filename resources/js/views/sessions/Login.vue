<template>
    <div>
        <v-layout
            align-center
            justify-center>
            <v-flex
                xs12
                sm8
                md6>
                <v-card
                    class="elevation-12">
                    <v-toolbar
                        color="blue-grey darken-3"
                        dark
                        >
                        <v-toolbar-title>SimaTakip | Sisteme Giriş</v-toolbar-title>
                        <v-spacer/>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="form" v-model="valid">
                            <v-text-field
                                ref="email"
                                v-model="email"
                                :rules="[rules.required, rules.email]"
                                prepend-icon="mdi-account"
                                label="E-Mail"
                                required
                                outlined
                                class="mt-6"
                            />
                            <v-text-field
                                ref="password"
                                v-model="password"
                                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                :rules="[rules.required, rules.min]"
                                :type="showPassword ? 'text' : 'password'"
                                prepend-icon="mdi-lock"
                                label="Şifre"
                                hint="En az 8 karakter"
                                required
                                @keydown.enter="login"
                                @click:append="showPassword = !showPassword"
                                outlined=""
                            ></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-divider class="mt-5"/>
                    <v-card-actions>
                        <v-spacer/>
                        <v-btn
                            align-center
                            justify-center
                            color="blue-grey darken-3"
                            dark
                            @click="login">Giriş
                        </v-btn>
                        <v-btn text to="/password/forgot">
                            <span>Şifremi Unuttum</span>
                            <v-icon right>mdi-exit-to-app</v-icon>
                        </v-btn>
                    </v-card-actions>
                    <v-snackbar
                        v-model="snackbar"
                        :color="color"
                        :top='true'
                    >
                        {{ errorMessages }}
                        <v-btn
                            dark
                            text
                            @click="snackbar = false"
                        >
                            Close
                        </v-btn>
                    </v-snackbar>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                email: '',
                rules: {
                    required: value => !!value || 'Gerekli Alan',
                    counter: value => value.length <= 20 || 'Maksimum 20 karakter',
                    min: v => v.length >= 8 || 'Minimum 8 karakter',
                    email: value => {
                        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                        return pattern.test(value) || 'Geçersiz e-mail formatı'
                    },
                },
                password: '',
                errorMessages: 'Giriş Bilgileri Hatalı',
                snackbar: false,
                color: 'general',
                showPassword: false,
                valid: false
            }
        },
        // Sends action to Vuex that will log you in and redirect to the dash otherwise, error
        methods: {
            login: function () {
                 if (this.$refs.form.validate()) {
                    const user = {
                        email: this.email,
                        password: this.password
                    };
                    this.$store.dispatch('login', { user })
                        .then(() => this.$router.push('/select_role'))
                        .catch(err => {
                                ;
                            }
                    )
                 }
                 else {
                     console.log('eksik');
                    this.color = 'error';
                    this.snackbar = true;
                    this.errorMessages = 'Lütfen Eksik Alanları Doldurun';
                 }
            }
        },
        metaInfo () {
            return {
                title: 'Süper Gizli | Giriş'
            }
        }
    }
</script>
