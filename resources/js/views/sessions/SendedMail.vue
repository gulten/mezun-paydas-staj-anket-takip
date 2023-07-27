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
                        <v-toolbar-title>SimaTakip | Bilgi</v-toolbar-title>
                        <v-spacer/>
                    </v-toolbar>
                    <v-card-text>
                        <p class="text-left">İşleminiz Gerçekleştirilmiştir. Lütfen Kontrol Ediniz.</p>
                     </v-card-text><v-divider class="mt-5"/>
                    <v-card-actions>
                        <v-spacer/>
                        <v-btn text to="/login">
                            <span>Giriş Ekranına Dön</span>
                            <v-icon right>mdi-exit-to-app</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
            }
        },
        methods: {
        },
    }
</script>
