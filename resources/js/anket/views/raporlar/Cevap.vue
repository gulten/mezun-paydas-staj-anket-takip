<template>
    <div>
        <v-snackbar top :timeout="timeout" v-model="snackbar">
            {{ snackbarMessage }}
        </v-snackbar>
        <v-dialog
            v-model="dialog"
            max-width="60%"
            >
            <v-card>
                <v-card-title class="headline text-center">{{ soru }}</v-card-title>
                <v-card-text class="mt-3 mb-3">
                    <div class="small">
                        <pie-chart :chart-data="datacollection"></pie-chart>
                    </div>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="green darken-1"
                    text
                    @click="dialog = false"
                >
                    Kapat
                </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog
            v-model="dialogTable"
            max-width="60%"
            >
            <v-card>
                <v-card-title class="headline text-center">{{ soru }}</v-card-title>
                <v-card-text class="mt-3 mb-3">
                        <v-data-table
                            :headers="headers"
                            :items="Items"
                            :items-per-page="25"
                        ></v-data-table>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="green darken-1"
                    text
                    @click="dialogTable = false"
                >
                    Kapat
                </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <div class="container">
            <template>
                <v-card
                    class="overflow-hidden"
                    color="lighten-1"
                >
                    <v-toolbar
                        flat
                    >
                        <v-icon>mdi-account-box-multiple-outline</v-icon>
                        <v-toolbar-title class="title"> {{ anket.ad }} / Anketi Dolduran Kişi :  {{ anket.name }}  </v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <template v-for="(item, index) in anketSorulari">
                            <div class="row">
                                <div class="col-8">
                                    <span v-if="item.soru.soru_tipi === 'VRating' || item.soru.soru_tipi === 'VSwitch'" class="caption">{{ (item.soru.required)?item.soru.soru + '*':item.soru.soru }}</span>
                                    <component
                                            disabled
                                            :key="item.soru.soru_tipi + index"
                                            :is="componentIs(item.soru.soru_tipi)"
                                            :items="item.soru.soru_tipi==='VSelect'?item.soru.detay:''"
                                            item-value="secenek"
                                            item-text="secenek"
                                            v-bind:outlined= "true"
                                            v-bind:label= "(item.soru.required)?item.soru.soru + '*':item.soru.soru"
                                            v-model="item.cevap"
                                    >
                                    <template v-if="item.soru.soru_tipi==='VRadio'||item.soru.soru_tipi==='VSwitch'">
                                        <template v-for="(item2, index2) in item.soru.detay">
                                            <component
                                                disabled
                                                :key="item2.tip + index2"
                                                :is="(item2.tip === 'VTextField')?'VRadio':item2.tip"
                                                v-bind:value= "item2.secenek"
                                                v-bind:label= "item2.secenek"
                                                v-bind:outlined= "true"
                                            >
                                            </component>
                                            <component v-if="(item2.tip === 'VTextField')&&(item.soru.text)"
                                                disabled
                                                :key="item2.tip + index2 + 'text'"
                                                :is="item2.tip"
                                                v-bind:label= "item2.secenek"
                                                v-bind:outlined= "true"
                                                v-model="item.soru.digerCevap"
                                            >
                                            </component>
                                        </template>
                                    </template>
                                    </component>
                                </div>
                                <div class="col-2">
                                    <div class="my-2">
                                        <v-btn
                                        depressed
                                        small
                                        color="primary"
                                        dark
                                        @click="viewChart(item)"
                                        >
                                            <v-icon left>mdi-chart-arc</v-icon> Soru İstatistik
                                        </v-btn>
                                    </div>
                                </div>
                            </div>
                            <v-divider></v-divider>
                        </template>
                    </v-card-text>
                </v-card>
            </template>
        </div>
    </div>
</template>

<script>
import PieChart from '../../components/chart/pie'
    export default {
        components:{
            PieChart
        },
        data: () => ({
            snackbar: false,
            snackbarMessage: '',
            timeout: 2000,
            anket: [],
            anketSorulari: [],
            complate: 0,
            datacollection:{ labels:[], datasets: [] },
            dialog: false,
            dialogTable: false,
            soru: '',
            headers: [
            { text: 'Kullanıcı', value: 'user.name' },
            { text: 'Cevap', value: 'cevap' },
            ],
            Items: []
        }),
        mounted(){
            this.getData();
        },

        methods: {
            getData () {
                api.get('/anket/rapor-cevap/' + this.$route.params.id)
                    .then(response => {
                        if(response.data.success) {
                            this.anket = response.data.data.anket;
                            if(response.data.data.cevaplar){
                                this.anketSorulari = response.data.data.cevaplar;
                            }
                        }
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
            viewChart(item) {
                this.soru = item.soru.soru;
                if (item.soru.soru_tipi==='VRadio' || item.soru.soru_tipi === 'VRating' || item.soru.soru_tipi === 'VSwitch') {
                    api.get('/anket/rapor-chart/' + item.id)
                    .then(response => {
                        if(response.data.success) {
                            this.dialog = true;
                            let labels = [];
                            let dataset = [];

                            response.data.data.chart.forEach(function(row) {
                                labels.push(row.cevap);
                                dataset.push(row.user_count);
                            }, this);

                            this.datacollection = {
                                labels: labels,
                                datasets: [
                                    {
                                        label: this.soru,
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.9)',
                                            'rgba(54, 162, 235, 0.9)',
                                            'rgba(255, 206, 86, 0.9)',
                                            'rgba(75, 192, 192, 0.9)',
                                            'rgba(153, 102, 255, 0.9)',
                                            'rgba(255, 159, 64, 0.9)'
                                        ],
                                        borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)',
                                            'rgba(75, 192, 192, 1)',
                                            'rgba(153, 102, 255, 1)',
                                            'rgba(255, 159, 64, 1)'
                                        ],
                                        borderWidth: 1,

                                        data: dataset
                                    }
                                ]
                            }
                        }
                    })
                }
                else {
                    api.get('/anket/rapor-table/' + item.id)
                    .then(response => {
                        if(response.data.success) {
                            this.dialogTable = true;
                            this.soru = item.soru.soru;
                            this.Items = response.data.data.table;
                        }
                     })
                }

            },
        },
    }
</script>
<style>
  .small {
    max-width: 400px;
    margin:  0px auto;
  }
</style>
