<template>
    <div>
        <v-layout
            align-center
            justify-center>
            <v-flex
                xs12
                sm8
                md4>
                <v-card
                    class="elevation-12">
                    <v-toolbar
                        color="blue-grey darken-3"
                        dark
                        >
                        <v-toolbar-title>SimaTakip | Şifre Sıfırlama</v-toolbar-title>
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
                                hint="At least 8 characters"
                                required
                                @keydown.enter="login"
                                @click:append="showPassword = !showPassword"
                                outlined=""
                            ></v-text-field>

                            <v-text-field
                                ref="password"
                                v-model="password_confirmation"
                                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                :rules="[rules.required, rules.min]"
                                :type="showPassword ? 'text' : 'password'"
                                prepend-icon="mdi-lock"
                                label="Şifre Tekrar"
                                hint="At least 8 characters"
                                required
                                @keydown.enter="login"
                                @click:append="showPassword = !showPassword"
                                outlined
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
                            @click="reset">Şifreyi Değiştir
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
                    required: value => !!value || 'Required.',
                    counter: value => value.length <= 20 || 'Max 20 characters',
                    min: v => v.length >= 8 || 'Min 8 characters',
                    email: value => {
                        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                        return pattern.test(value) || 'Invalid e-mail.'
                    },
                },
                password: '',
                password_confirmation: '',
                errorMessages: 'Giriş Bilgileri Hatalı',
                snackbar: false,
                color: 'general',
                showPassword: false,
                valid: false
            }
        },
        // Sends action to Vuex that will log you in and redirect to the dash otherwise, error
        methods: {
            reset: function () {
                 if (this.$refs.form.validate()) {
                    const user = {
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                        token: this.$route.params.token
                    };
                    this.$store.dispatch('reset', { user })
                        .then(() => this.$router.push('/sended/mail'))
                        .catch(err => {
                                console.log(err);
                            }
                    )
                 }
            }
        },
    }
</script>
