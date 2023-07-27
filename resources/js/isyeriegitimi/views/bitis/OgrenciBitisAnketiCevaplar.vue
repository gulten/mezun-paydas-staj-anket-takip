<template>
    <div>
        <div class="container">
            <template>
                <v-card
                    class="overflow-hidden"
                    color="lighten-1"
                >
                    <v-toolbar
                        flat
                    >
                        <v-toolbar-title class="title">Cevapların çıktısını alarak bitiş belgelerine ekleyiniz</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn
                        @click="print"
                            color="darken-3"
                            fab
                            small
                            class="mr-2"
                        >
                            <v-icon>print</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card
                        id="print_anket"
                        class="overflow-hidden pa-6"
                        color="lighten-1"
                    >
                        <v-card-title>
                            {{ anket.ad }} / {{ anket.user }}
                        </v-card-title>
                        <v-card-text>
                            <template v-for="(item, index) in anketSorulari">
                                <v-divider :key="index" class="mt-0 mb-0"></v-divider>
                                <div :key="index" class="row">
                                    <div class="col-10">
                                        <span class="caption">{{ (item.soru.required)?item.soru.soru + '*':item.soru.soru }}</span>
                                        <template v-if="item.soru.soru_tipi === 'VRating' || item.soru.soru_tipi === 'VSwitch'">
                                            <component
                                                    disabled
                                                    :key="item.soru.soru_tipi + index"
                                                    :is="componentIs(item.soru.soru_tipi)"
                                                    :items="item.soru.soru_tipi==='VSelect'?item.soru.detay:''"
                                                    item-value="secenek"
                                                    item-text="secenek"
                                                    v-bind:outlined= "true"
                                                    v-model="item.cevap"
                                            >
                                            </component>
                                        </template>
                                        <template v-else>
                                            <div class="d-block mt-2 font-weight-bold">
                                            {{ item.cevap }}
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </v-card-text>
                    </v-card>
                </v-card>
            </template>
        </div>

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
        components:{
        },
        props: ['type', 'id'],
        data: () => ({
            snackbarMessage: 'Bir sorun oluştu',
            snackbar: false,
            color: 'general',
            anket: [],
            anketSorulari: [],
            complate: 0,
        }),
        mounted(){
            this.getData();
        },

        methods: {
            getData () {
                api.get('/isyeriegitimi/bitis-anketi/ogrenci/cevaplar')
                    .then(response => {
                        if(response.data.success) {
                            this.anket = response.data.data.anket;

                            if(response.data.data.anket_cevaplar){
                                this.anketSorulari = response.data.data.anket_cevaplar;
                                this.complate = 1;
                            }
                            else{
                                this.complate = 2;
                            }
                        }
                    })
                    .catch(err => {
                        this.color = 'error';
                        this.snackbar = true;
                        this.snackbarMessage = err.response.data.message;
                    });
            },
            print () {
                this.$htmlToPaper('print_anket');
            },
            componentIs(type){
                if(type==='VRadio')
                    return 'VRadioGroup';
                else if(type==='VSwitch')
                    return 'VSheet';
                else
                    return type;
            },

        },
    }
</script>
