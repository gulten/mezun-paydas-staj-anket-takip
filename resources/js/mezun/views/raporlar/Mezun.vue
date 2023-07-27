<template>
    <div>
        <v-snackbar top :timeout="timeout" v-model="snackbar">
            {{ snackbarMessage }}
        </v-snackbar>
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
                                    label="Kayıt Tarihi"
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
                                    label="Mezuniyet Tarihi"
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
                        <v-col cols="12">
                            <v-autocomplete
                                v-model="kayit_sekli"
                                :items="kayitSekli"
                                label="Kayıt Şekli"
                                return-object
                                dense
                                chips
                                small-chips
                                clearable
                                outlined
                                multiple
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
                        label="Öğrenci No, Ad veya E-Mail Filtrele"
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
                                <v-toolbar-title>MEZUN BİLGİLERİ RAPORLAMA</v-toolbar-title>
                                <v-divider
                                    class="mx-4"
                                    inset
                                    vertical
                                ></v-divider>
                                <v-spacer></v-spacer>
                                <template>
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
    import { Roboto } from '../../../helpers/helpers'
    export default {
        components:{
            jsPDF,
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
                { text: 'ÖĞRENCİ NUMARASI', value: 'ogrenci_no' },
                { text: 'AD', value: 'name' },
                { text: 'E-MAIL', value: 'email' },
                { text: 'KAYIT ŞEKLİ', value: 'kayit_sekli', filterable: false },
                { text: 'BÖLÜM GİRİŞ TARİHİ', value: 'kayit_yili', filterable: false },
                { text: 'MEZUNİYET TARİHİ', value: 'mezuniyet_tarihi', filterable: false },
                { text: 'MEZUNİYET SÜRESİ', value: 'mezuniyet_suresi', filterable: false },
                { text: 'TELEFON', value: 'telefon', filterable: false },
            ],
            Items: [],
            start_date: moment.utc().local().format("YYYY-MM-DD"),
            end_date: moment.utc().local().format("YYYY-MM-DD"),
            tumDersler: [],
            ders_id: 0,
            heads :  [
                { text: 'ÖĞRENCİ NUMARASI', value: 'ogrenci_no' },
                { text: 'AD', value: 'name' },
                { text: 'E-MAIL', value: 'email' },
                { text: 'KAYIT ŞEKLİ', value: 'kayit_sekli', filterable: false },
                { text: 'BÖLÜM GİRİŞ TARİHİ', value: 'kayit_yili', filterable: false },
                { text: 'MEZUNİYET TARİHİ', value: 'mezuniyet_tarihi', filterable: false },
                { text: 'MEZUNİYET SÜRESİ', value: 'mezuniyet_suresi', filterable: false },
                { text: 'TELEFON', value: 'telefon', filterable: false },
            ],
            graph: [],
            graphField: 'icerige_yonelik',
            graphFieldsList: [
                { text: 'MEZUNİYET TARİHİ', value: 'mezuniyet_tarihi' },
                { text: 'MEZUNİYET SÜRESİ', value: 'mezuniyet_suresi' },
                { text: 'TELEFON', value: 'telefon' },
            ],
            chart: [],
            kayit_sekli: [],
            kayitSekli: [
                'bilinmiyor',
                'YGS',
                'MTOK',
                'DGS',
                'Yatay Geçiş',
                'Mühendislik Tamamlama',
                'Diger'
            ],
            ortalamaSure: 0,
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
        },

        methods: {
            getUsers () {
                let formData = new FormData();
                formData.append('start_date', this.start_date);
                formData.append('end_date', this.end_date);
                formData.append('kayit_sekli', this.kayit_sekli);
                api.post('/mezun/mezun/rapor', JSON.stringify({
                        'start_date' : this.start_date,
                        'end_date' : this.end_date,
                        'kayit_sekli' : this.kayit_sekli
                    }
                    ))
                    .then(response => {
                        if (response.data.success) {
                            this.Items = [];
                            response.data.data.liste.forEach(function(item) {
                                    this.Items.push({
                                        'id' : item.id,
                                        'name': item.name,
                                        'email': item.email,
                                        'mezuniyet_tarihi': item.mezun.mezuniyet_tarihi,
                                        'mezuniyet_suresi': item.mezun.mezuniyet_suresi,
                                        'telefon': item.mezun.telefon,
                                        'ogrenci_no': item.ogrenci.ogrenci_no,
                                        'kayit_sekli': item.ogrenci.kayit_sekli,
                                        'kayit_yili': item.ogrenci.kayit_yili,
                                        'adres': item.ogrenci.adres,
                                    });

                            }, this);
                            this.selected = this.Items;
                            this.ortalamaSure = response.data.data.ortalamasure;
                        }
                    })
            },
            createPDF(){
                let heads = [];
                let body = [];
                let row = [];
                let FinalyText = [];

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

                FinalyText[0] = 'Mezuniyet Tarih Aralığı : ' + this.start_date + ' - ' + this.end_date;
                FinalyText[1] = 'Mezun Kişi Sayısı : ' + this.Items.length;
                FinalyText[2] = 'Ortalama Mezuniyet Süresi : ' + this.ortalamaSure;

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
                doc.text(40, doc.lastAutoTable.finalY + 20, FinalyText[0])
                doc.text(40, doc.lastAutoTable.finalY + 40, FinalyText[1])
                doc.text(40, doc.lastAutoTable.finalY + 60, FinalyText[2])
                doc.save('mezunlar.pdf');
            },
        },
    }
</script>
