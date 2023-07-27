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
                <v-card-title class="headline text-center">KALİTE SKORU ANALİZ VERİSİ</v-card-title>
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
        <div class="container">
            <v-card>
                <v-card-title class="elevation-1">
                    FİLTRELER
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer>
                    </v-spacer>
                    <v-btn @click="getUsers" color="primary" dark class="mb-2">
                        <v-icon left>mdi-layers-search-outline</v-icon>
                        FİLTRELERİ UYGULA
                    </v-btn>
                </v-card-title>
                <v-card-text>
                <v-row>
                        <v-col cols="12" sm="6" md="6">
                            <v-checkbox v-model="mezun" label="Mezun" :true-value=1 :false-value=0></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                            <v-checkbox v-model="paydas" label="Paydaş" :true-value=1 :false-value=0></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                            <v-menu
                                v-model="menu1"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                <v-text-field
                                    v-model="start_date"
                                    label="Başlangıç Tarihi"
                                    prepend-icon="mdi-calendar-blank-outline"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="start_date"
                                    @input="menu1 = false"
                                    locale="tr"
                                    >
                                    </v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                            <v-menu
                                v-model="menu2"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                <v-text-field
                                    v-model="end_date"
                                    label="Bitiş Tarihi"
                                    prepend-icon="mdi-calendar-blank-outline"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="end_date"
                                    @input="menu2 = false"
                                    locale="tr"
                                    :min="start_date"
                                    >
                                    </v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="6">
                            <v-autocomplete
                                v-model="ders_id"
                                :items="tumDersler"
                                label="Seçilen Ders"
                                item-value="id"
                                item-text="ders_adi"
                                dense
                                chips
                                small-chips
                                clearable
                                outlined
                                persistent-hint
                                hint="Eğer Tümünü Listelemek İsterseniz Seçim Yapmayınız"
                            >
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="3">
                            <v-autocomplete
                                v-model="skor_alt"
                                :items="tumAltSkorlar"
                                label="Skor Alt Limit"
                                dense
                                outlined
                                @change="changeUstSkor"
                            >
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="3">
                            <v-autocomplete
                                v-model="skor_ust"
                                :items="tumUstSkorlar"
                                label="Skor Üst Limit"
                                dense
                                outlined
                            >
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="4">
                            <v-autocomplete
                                v-model="donem"
                                :items="tumDonemler"
                                label="Seçili Dönem"
                                dense
                                chips
                                small-chips
                                clearable
                                outlined
                                persistent-hint
                                hint="Eğer Tümünü Listelemek İsterseniz Seçim Yapmayınız"
                            >
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="4">
                            <v-autocomplete
                                v-model="durum"
                                :items="tumDurumlar"
                                label="Seçili Durum"
                                dense
                                chips
                                small-chips
                                clearable
                                outlined
                                persistent-hint
                                hint="Eğer Tümünü Listelemek İsterseniz Seçim Yapmayınız"
                            >
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="4">
                            <v-autocomplete
                                v-model="sinif"
                                :items="tumSiniflar"
                                label="Seçili Sınıf"
                                dense
                                chips
                                small-chips
                                clearable
                                outlined
                                persistent-hint
                                hint="Eğer Tümünü Listelemek İsterseniz Seçim Yapmayınız"
                            >
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="12">
                            <v-divider></v-divider>
                        </v-col>
                        <v-col cols="12">
                        <v-autocomplete
                            v-model="headers"
                            :items="heads"
                            item-text="text"
                            return-object
                            label="Görüntülenecek Alanları Seçiniz"
                            dense
                            chips
                            small-chips
                            clearable
                            multiple
                            outlined
                        >
                        </v-autocomplete>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
            <v-card class="mt-4">
                <v-card-title>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-account-search"
                        label="Ad veya E-Mail Filtrele"
                        hide-details
                    ></v-text-field>
                </v-card-title>
                <v-card-text>
                    <v-data-table
                        v-model="selected"
                        :headers="headers"
                        :items="Items"
                        class="elevation-1"
                        sort-by="id"
                        :items-per-page="rowsPerPage"
                        :search="search"
                        show-select
                    >
                        <template v-slot:top>
                            <v-toolbar  class="elevation-1 mb-4" color="white">
                                <v-toolbar-title>DERSİN İŞLENİŞ KALİTESİNE YÖNELİK GÖRÜŞ VE ÖNERİLER</v-toolbar-title>
                                <v-divider
                                    class="mx-4"
                                    inset
                                    vertical
                                ></v-divider>
                                <v-spacer></v-spacer>
                                <template>
                                    <v-btn @click="[dialog = true, viewChart]" color="primary" dark class="mb-2">
                                        <v-icon left>mdi-chart-arc</v-icon>
                                        GRAFİK - ANALİZ
                                    </v-btn>
                                    <v-btn @click="createPDF" color="primary" dark class="mb-2 ml-3">
                                        <v-icon left>mdi-file-pdf</v-icon>
                                        Pdf Olarak Aktar
                                    </v-btn>
                                </template>
                            </v-toolbar>
                        </template>
                        <template v-slot:no-data>
                            <v-btn color="primary" @click="getUsers">Reset</v-btn>
                        </template>
                        <template v-slot:item.kalite="{ item }">
                            <v-rating
                                v-model="item.kalite"
                                :length="length"
                                background-color="purple lighten-3"
                                color="purple"
                                small
                                readonly
                            ></v-rating>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </div>
    </div>
</template>
<script>
    import moment from 'moment';
    import jsPDF from 'jspdf';
    import 'jspdf-autotable';
    import PieChart from '../../../components/chart/pie';
    import { Roboto } from '../../../helpers/helpers';
    export default {
        components:{
            jsPDF,
            PieChart
        },
        data: () => ({
            search: '',
            selected: [],
            rowsPerPage: 25,
            menu: false,
            menu1: false,
            menu2: false,
            active: null,
            dialog: false,
            datacollection:{ labels:[], datasets: [] },
            show: false,
            buttonLoader: false,
            validForm: true,
            snackbar: false,
            snackbarMessage: '',
            timeout: 2000,
            mezun: 1,
            paydas: 0,
            headers: [
                { text: 'AD', value: 'name' },
                { text: 'E-MAIL', value: 'email' },
                { text: 'DERS ADI', value: 'ders_adi', filterable: false },
                { text: 'KALİTE SKORU (1-5 Arası)', value: 'kalite', filterable: false },
                { text: 'GÖRÜŞÜNÜZ', value: 'sebep', filterable: false },
            ],
            Items: [],
            start_date: moment.utc().local().format("YYYY-MM-DD"),
            end_date: moment.utc().local().format("YYYY-MM-DD"),
            tumDersler: [],
            ders_id: 0,
            heads :  [
                { text: 'AD', value: 'name' },
                { text: 'E-MAIL', value: 'email' },
                { text: 'DERS ADI', value: 'ders_adi', filterable: false },
                { text: 'DURUM', value: 'durum', filterable: false },
                { text: 'DÖNEM', value: 'donem', filterable: false },
                { text: 'SINIF', value: 'sinif', filterable: false },
                { text: 'KALİTE SKORU (1-5 Arası)', value: 'kalite', filterable: false },
                { text: 'GÖRÜŞÜNÜZ', value: 'sebep', filterable: false },
                { text: 'TARİH', value: 'created_at', filterable: false },
            ],
            graph: [],
            graphField: 'icerige_yonelik',
            graphFieldsList: [
                { text: 'KALİTE SKORU (1-5 Arası)', value: 'kalite' },
                { text: 'GÖRÜŞÜNÜZ', value: 'sebep' },
            ],
            donem: '',
            tumDonemler: ['Bahar', 'Güz'],
            durum: '',
            tumDurumlar: ['Zorunlu', 'Seçmeli'],
            sinif: '',
            tumSiniflar: ['Hazırlık', '1', '2', '3', '4'],
            chart: [],
            length: 5,
            tumAltSkorlar: [1,2,3,4,5],
            tumUstSkorlar: [1,2,3,4,5],
            skor_alt: 1,
            skor_ust: 5
        }),

        computed: {
        },

        watch: {
            start_date: function (val) {
                if (this.end_date < val)
                this.end_date = val;
            },
        },

        created () {
            this.getUsers();
            this.tumDersleriGetir();
        },

        methods: {
            tumDersleriGetir(){
                api.get('/mezun/rapor/tum-dersler')
                    .then(response => {
                        if (response.data.success) {
                            this.tumDersler = response.data.data.dersler;
                        }
                    })
            },
            getUsers () {
                let formData = new FormData();
                formData.append('mezun', this.mezun);
                formData.append('paydas', this.paydas);
                formData.append('start_date', this.start_date);
                formData.append('end_date', this.end_date);
                formData.append('ders_id', this.ders_id || 0);
                formData.append('donem', this.donem || '');
                formData.append('durum', this.durum || '');
                formData.append('sinif', this.sinif || '');
                formData.append('skor_alt', this.skor_alt || 1);
                formData.append('skor_ust', this.skor_ust || 5);
                api.post('/mezun/ders-kalite/rapor', formData)
                    .then(response => {
                        if (response.data.success) {
                            this.Items = [];
                            response.data.data.liste.forEach(function(item) {
                                    this.Items.push({
                                        'id' : item.id,
                                        'name': item.user.name,
                                        'email': item.user.email,
                                        'ders_adi': item.bolumders.ders_adi,
                                        'donem': item.bolumders.donem,
                                        'durum': item.bolumders.durum,
                                        'sinif': item.bolumders.sinif,
                                        'kalite': item.kalite,
                                        'sebep': item.sebep,
                                        'created_at': item.created_at,
                                    });

                            }, this);
                            this.selected = this.Items;
                            this.chart = response.data.data.chart;
                            this.viewChart();
                        }
                    })
            },
            createPDF(){
                let heads = [];
                let body = [];
                let row = [];

                this.headers.forEach(function(item) {
                    heads.push([
                            item.text
                    ]);
                }, this);


                this.selected.forEach(function(item) {
                    row = [];

                    this.headers.forEach(function(head) {
                        row.push(
                            item[head.value]
                        );

                    }, this);

                    body.push(
                        row
                    );

                    /*body.push([
                            item.name,
                            item.email,
                            item.ders_adi,
                            item.amaca_yonelik,
                            item.icerige_yonelik,
                            item.ders_yariyilina_saatine_yonelik,
                            item.diger
                    ]);*/
                }, this);

                let doc = new jsPDF({
                        orientation: "landscape",
                        unit: "pt",
                        format: "letter"
                    });

                doc.addFileToVFS('roboto-regular-normal.ttf', Roboto);
                doc.addFont('roboto-regular-normal.ttf', 'roboto-regular', 'normal');
                doc.setFont('roboto-regular');
                doc.setFontStyle('normal')
                doc.setFontSize(10);

                doc.autoTable({
                    head: [heads],
                    body: body,
                    "startY": 45,
                    "styles": { "overflow": "linebreak", "minCellHeight": "50", "cellWidth": "wrap", "rowPageBreak": "avoid", "halign": "justify", "font": "roboto-regular", "fontStyle": "normal", "fontSize": "8", "lineColor": "100", "lineWidth": ".25" },
                    "columnStyles": { "[0]": { "halign": "left", "cellWidth": "auto"}, "[1]": { "halign": "left", "cellWidth": "auto" }, "[2]": { "halign": "left"}, "[3]": { "halign": "left", "cellWidth": "auto" }, "[4]": {  "halign": "left", "cellWidth": "auto" }, "[5]": {  "halign": "left", "cellWidth": "auto" }, "[6]": {  "halign": "left", "cellWidth": "auto" }, "[7]": {  "halign": "left", "cellWidth": "auto" } },
                    "theme": "grid",
                    "pageBreak": "auto",
                    "tableWidth": "auto",
                    "showHead": "everyPage",
                    "showFoot": "everyPage",
                    "tableLineWidth": 0,
                    "tableLineColor": 200,
                    "margin": { "top": 30 },
                });//autoTable
                doc.save('derskalite.pdf');
            },
            viewChart() {
                let labels = [];
                for (let i=1; i<=5; i++)
                    labels.push(i);
                let dataset = [];

                this.datacollection = {
                    labels: labels,
                    datasets: [
                        {
                            label: "KALİTE SKORU ANALİZ VERİSİ",
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

                            data: this.chart
                        }
                    ]
                }
            },
            changeUstSkor(){
                this.tumUstSkorlar = [];
                for(let i=this.skor_alt; i<=5; i++)
                    this.tumUstSkorlar.push(i);

                this.skor_ust = 5;
            }
        },
    }
</script>
