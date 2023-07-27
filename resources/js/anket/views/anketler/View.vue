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
                    <v-icon>mdi-account-box-multiple-outline</v-icon>
                    <v-toolbar-title class="title"> {{ anket.ad }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn
                        :to="`/anket/${type}/edit`"
                        color="darken-3"
                        fab
                        small
                    >
                        <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>
                    <template v-for="(item, index) in anketSorulari">
                        <div :key="index" class="row">
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
        </div>
    </div>
</template>

<script>
    export default {
        components:{
        },
        props: ['type', 'id'],
        data: () => ({
            anket: [],
            anketSorulari: [],
        }),
        mounted(){
            this.getData();
        },

        methods: {
            getData () {
                api.get('anket/sorular/' + this.id)
                    .then(response => {
                        if(response.data.success) {
                            this.anket = response.data.data;
                            this.anketSorulari = response.data.data.anket_sorular;
                        }
                    })
                    .catch(error => console.log(error))
            },
            save () {

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
