
<template>
    <div>
        <v-container>
            <v-row dense>
                <template v-if="basvuruControl===1">
                    <v-col cols="6">
                        <v-card
                        color="#385F73"
                        dark
                        >
                            <v-card-title class="headline">
                                Aktif Bir Başvurunuz Bulunmaktadır
                            </v-card-title>
                            <v-divider></v-divider>
                            <v-card-subtitle>
                                        <span>Firma :</span>
                                        <span v-html="this.basvuru.firma.adi"></span>
                                       <br>
                                        <span>İşyeri Eğitimi Tarihleri :</span>
                                        <span v-html="this.basvuru.baslangic_tarihi"></span>
                                        <span> / </span>
                                        <span v-html="this.basvuru.bitis_tarihi"></span>
                                        <br>
                                        <span v-if="this.basvuru.statu">İşyeri Eğitimi Dönem Durumu : Tamamlandı</span>
                            </v-card-subtitle>
                             <v-divider></v-divider>
                            <v-card-actions>
                                <v-btn text href="/isyeriegitimi/basvuru-sureci/basvuru">
                                    Detaylar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </template>

                <template v-if="basvuruControl===2">
                    <v-col cols="6">
                        <v-card
                        color="teal darken-2"
                        dark
                        >
                            <v-card-title v-if="tamamlanan" class="headline">
                                    İşyeri Eğitiminiz Tamamlandı
                            </v-card-title>
                        </v-card>
                    </v-col>
                </template>

                <template v-if="sumDateVisible">
                    <v-col cols="6">
                        <v-card
                        color="orange darken-2"
                        dark
                        >
                            <v-card-title class="headline">
                                    İşyeri Eğitimini Yaptığınız Toplam Gün Sayısı: {{ toplamGun }}
                            </v-card-title>
                            <v-card-text class="headline">
                                    İşyeri Eğitimini Kalan Toplam Gün Sayısı: {{ kalanGun }}
                            </v-card-text>
                        </v-card>
                    </v-col>
                </template>

            </v-row>
        </v-container>


    </div>
</template>

<script>
import moment from 'moment';
import business from 'moment-business-days';
export default {
    data () {
      return {
        role: '',
        basvuru: [],
        basvuruControl: 0,
        e6: 1,
        dilekceIndir: null,
        basvuruKabul: null,
        isyeriBasvuruKase: null,
        isyeri: {
            id: null,
            adi: null,
            telefon: null,
            email: null
        },
        isyeriList: [],
        stringRules: [
            v => !!v || 'Bu alan gereklidir',
        ],
        emailRules: [
            v => !!v || 'E-mail alanı gereklidir',
            v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail formatı yanlış',
        ],
        phoneRules: [
            v => !!v || 'Telefon alanı gereklidir',
            v => (/^(\s)?\d{1}(\()\d{3}(\))(\s)\d{3}(\s)\d{2}(\s)\d{2}$/.test(v)&&v.length>0) || 'Telefon formatı hatalıdır',
        ],
        isyeriForm: null,
        menu: false,
        menu2: false,
        baslamaTarihi: '',
        bitisTarihi: '',
        isyeriTarihiKontrol: null,
        cumartesiCalisma: false,
        days: 5,
        calcDays: 0,
        saglikProvizyon: null,
        saglikYardimi: null,
        IsgTemelEgitim: null,
        start_date: null,
        end_date: null,
        EvrakTeslim: true,
        belgeTeslimTarihi : null,
        snackbarMessage: 'Bir sorun oluştu',
        snackbar: false,
        color: 'general',
        isLoading: false,
        searchFirma: null,
        isyeri_input_disable: false,
        firmaDurum: false,
        bitis_start_date: '',
        bitis_end_date: '',
        mulakat_start_date: '',
        mulakat_end_date: '',
        toplamGun: 0,
        sumDateVisible: false,
        tamamlanan: null,
        kalanGun: 0
      }

    },
    computed: {
    },
    watch: {
    },
    created () {
        this.createdMethods();
    },
    methods: {
        createdMethods(){
            this.getRole();
            if(this.role==='Ogrenci') {
                this.getControl();
                this.getDates();
                this.getSumDates();
            }
        },
        getRole(){
            this.role = this.$store.getters.role || localStorage.getItem('role');
        },
        getControl () {
            api.get('/isyeriegitimi/basvuru')
                .then(response => {
                    if (response.data.success) {
                        this.basvuru = response.data.data;
                        if (this.basvuru.id) {
                            if (this.basvuru.tamamlanan.statu==='tamamlandı') {
                                this.basvuruControl = 2;
                            }
                            else {
                                this.basvuruControl = 1;
                                this.start_date = response.data.data.baslangic_tarihi;
                                this.end_date = response.data.data.bitis_tarihi;
                                this.belgeTeslimTarihi = moment(this.end_date).subtract(30, 'days').format("YYYY-MM-DD");
                            }
                        }
                        else {
                            if (this.basvuru.tamamlanan.statu==='tamamlandı') {
                                this.basvuruControl = 2;
                            }
                            else {
                                this.basvuruControl = 0;
                            }
                        }
                    }
                })
                .catch(err => {
                    this.color = 'error';
                    this.snackbar = true;
                    this.snackbarMessage = 'Kaydınıza Ulaşılamadı';
                });
        },
        getDates () {
            api.get('/isyeriegitimi/islem-tarihleri')
                .then(response => {
                    if (response.data.success) {
                        this.start_date = response.data.data.basvuru_tarih.islemtarih.baslangic_tarihi;
                        this.end_date = response.data.data.basvuru_tarih.islemtarih.bitis_tarihi;
                        this.belgeTeslimTarihi = moment(this.end_date).subtract(30, 'days').format("YYYY-MM-DD");

                        this.bitis_start_date = response.data.data.bitis_tarih.islemtarih.baslangic_tarihi;
                        this.bitis_end_date = response.data.data.bitis_tarih.islemtarih.bitis_tarihi;

                        this.mulakat_start_date = response.data.data.mulakat_tarih.islemtarih.baslangic_tarihi;
                        this.mulakat_end_date = response.data.data.mulakat_tarih.islemtarih.bitis_tarihi;
                    }
                }
            );
        },
        getSumDates(){
            api.get('/isyeriegitimi/basvuru/toplam')
                .then(response => {
                    if (response.data.success) {
                        this.toplamGun = response.data.data.toplam_gun;
                        this.kalanGun = response.data.data.kalan_gun;
                        this.tamamlanan = response.data.data.bitis_durum;
                        this.sumDateVisible = true;
                    }
                })
                .catch(err => {
                    this.color = 'error';
                    this.snackbar = true;
                    this.snackbarMessage = 'Kaydınıza Ulaşılamadı';
                });
        }

    },
}
</script>

<style>

</style>
