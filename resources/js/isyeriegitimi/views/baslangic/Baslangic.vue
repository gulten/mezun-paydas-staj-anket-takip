
<template>
    <div>
        <v-card flat outlined class="mb-12" min-height="200px">
            <v-card-text>
                <span class="text-caption">İşyeri eğitimi tarihlerinize göre işe giriş-çıkış bildirgeniz hazırlandı mı?</span>
                <v-radio-group v-model="bildirge" column>
                    <v-radio label="Evet" :value=true></v-radio>
                    <v-radio label="Hayır" :value=false></v-radio>
                </v-radio-group>
                <v-alert
                    outlined
                    type="success"
                    prominent
                    border="left"
                    v-if="bildirge === true"
                    >
                    Staj başvuru formunda belirttiğiniz tarihler arasında stajınızı yapabilirsiniz
                </v-alert>
                <v-alert
                    outlined
                    type="warning"
                    prominent
                    border="left"
                    v-if="bildirge === false"
                    >
                    0 (462) 377 3800 nolu telefonu arayarak KTÜ Sağlık ve Spor Daire Başkanlığı'nın "Staj Birimi" ne  bilgi veriniz. Stajınıza başlamayınız.
                </v-alert>
            </v-card-text>
        </v-card>

        <v-snackbar
            v-model="snackbar"
            :color="color"
            :top='true'
        >
            {{ snackbarMessage }}
            <v-btn
                dark
                text
                @click="snackbar = false"
            >
                Kapat
            </v-btn>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    data () {
      return {
        bildirge: null,
        snackbarMessage: 'Bir sorun oluştu',
        snackbar: false,
        color: 'general',
      }
    },
    watch: {
    },
    created () {
    },
    methods: {
    },
}
</script>

<style>

</style>
