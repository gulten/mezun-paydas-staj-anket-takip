<template>
    <div>
        <v-snackbar top :timeout="timeout" v-model="snackbar">
            {{ snackbarMessage }}
        </v-snackbar>
        <div class="container">
            <template v-if="complate===1">
                <v-card
                    class="overflow-hidden"
                    color="lighten-1"
                >
                    <v-toolbar
                        flat
                    >
                        <v-icon>mdi-account-box-multiple-outline</v-icon>
                        <v-toolbar-title class="title"> {{ anket.ad }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn
                        @click="save"
                            color="darken-3"
                            fab
                            small
                            class="mr-2"
                        >
                            <v-icon>mdi-content-save</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <template v-for="(item, index) in anketSorulari">
                            <div class="row">
                                <div class="col-8">
                                    <span v-if="item.soru_tipi === 'VRating' || item.soru_tipi === 'VSwitch'" class="caption">{{ (item.required)?item.soru + '*':item.soru }}</span>
                                    <component
                                            :key="item.soru_tipi + index"
                                            :is="componentIs(item.soru_tipi)"
                                            :items="item.soru_tipi==='VSelect'?item.detay:''"
                                            item-value="secenek"
                                            item-text="secenek"
                                            v-bind:outlined= "true"
                                            v-bind:label= "(item.required)?item.soru + '*':item.soru"
                                            v-model="item.cevap"
                                    >
                                    <template v-if="item.soru_tipi==='VRadio'||item.soru_tipi==='VSwitch'">
                                        <template v-for="(item2, index2) in item.detay">
                                            <component
                                                :key="item2.tip + index2"
                                                :is="(item2.tip === 'VTextField')?'VRadio':item2.tip"
                                                v-bind:value= "item2.secenek"
                                                v-bind:label= "item2.secenek"
                                                v-bind:outlined= "true"
                                                @change="(item2.tip === 'VTextField')?item.text=1:item.text=0"
                                            >
                                            </component>
                                            <component v-if="(item2.tip === 'VTextField')&&(item.text)"
                                                :key="item2.tip + index2 + 'text'"
                                                :is="item2.tip"
                                                v-bind:label= "item2.secenek"
                                                v-bind:outlined= "true"
                                                v-model="item.digerCevap"
                                            >
                                            </component>
                                        </template>
                                    </template>
                                    </component>
                                </div>
                            </div>
                            <v-divider></v-divider>
                        </template>
                    </v-card-text>
                </v-card>
            </template>
            <template v-else-if="complate===2">
                <v-card
                    class="overflow-hidden"
                    color="lighten-1"
                >
                    <v-card-text>
                            Bu anket {{ anket.updated_at }} tarihinde gönderilmiştir. Teşekkür Ederiz.
                    </v-card-text>
                </v-card>
            </template>
            <template v-else-if="complate===0">
                <v-progress-linear
                indeterminate
                color="cyan"
                ></v-progress-linear>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        components:{
        },
        props: ['type', 'id'],
        data: () => ({
            snackbar: false,
            snackbarMessage: '',
            timeout: 2000,
            anket: [],
            anketSorulari: [],
            complate: 0
        }),
        mounted(){
            this.getData();
        },

        methods: {
            getData () {
                api.get('mezun/mezun-anket')
                    .then(response => {
                        if(response.data.success) {
                            this.anket = response.data.data;

                            if(response.data.data.anket_sorular){
                                this.anketSorulari = response.data.data.anket_sorular;
                                this.complate = 1;
                            }
                            else{
                                this.complate = 2;
                            }
                        }
                    })
            },
            save () {
                this.anket.anket_sorular = this.anketSorulari;
                console.log(this.anket.anket_sorular);
                api.post('mezun/mezun-anket', this.anket)
                    .then(response => {
                        if(response.data.success) {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarılı';
                            this.anket = response.data.data;
                            this.complate = 2;
                        }
                    })
                    .catch(error => {
                        this.snackbar = true;
                        this.snackbarMessage = 'İşlem Başarısız, Lütfen Bilgileri Kontrol Ediniz';
                    })
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
