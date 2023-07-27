
<template>
    <div>
        <template v-if="basvuruControl===2">
            <v-card
                class="overflow-hidden"
                color="lighten-1"
            >
                <v-card-text>
                        Şu an yeni bir talepte bulunamazsınız.
                </v-card-text>
            </v-card>
        </template>
        <template v-else-if="basvuruControl===1">
            <v-card
                class="mx-auto"
                max-width="500"
                outlined
            >
            <v-card-title class="grey darken-3">
                <span class="headline white--text">Aktif Bir Başvurunuz Bulunmaktadır</span>
                <v-spacer></v-spacer>
                <v-btn
                    v-if="this.basvuru.islem_id===1"
                    small
                    fab
                    @click="deleteItem"
                >
                <v-icon>mdi-delete</v-icon>
                </v-btn>
            </v-card-title>

            <v-list-item>
                <v-list-item-content>
                    <span>İşyeri Eğitimi Başlangıç Tarihi</span>
                </v-list-item-content>
                <v-list-item-content>
                    <span v-html="this.basvuru.baslangic_tarihi"></span>
                </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
            <v-list-item>
                <v-list-item-content>
                    <span>İşyeri Eğitimi Bitiş Tarihi</span>
                </v-list-item-content>
                <v-list-item-content>
                    <span v-html="this.basvuru.bitis_tarihi"></span>
                </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
            <v-list-item>
                <v-list-item-content>
                    <span>Cumartesi Çalışma</span>
                </v-list-item-content>
                <v-list-item-content>
                    <v-checkbox
                            v-model="this.basvuru.cumartesi"
                            :disabled="true"
                        >
                    </v-checkbox>
                </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
            <v-list-item>
                <v-list-item-content>
                    <span>Firma Bilgileri</span>
                </v-list-item-content>
                <v-list-item-content>
                    <span v-html="this.basvuru.firma.adi"></span>
                    <span v-html="this.basvuru.firma.telefon"></span>
                    <span v-html="this.basvuru.firma.email"></span>
                </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
            <v-list-item>
                <v-list-item-content>
                    <span class="blue-grey--text" v-html="`Başvuru belgeleri ` + this.basvuru.basvuru_belge_teslim"></span>
                </v-list-item-content>
                <v-list-item-content v-if="this.basvuru.basvuru_belge_teslim===`bekleniyor`">
                    <span v-if="this.basvuruTeslimDurum" v-html="`Başvuru belgelerini komisyona <br> ` + this.belgeTeslimTarihi + ` tarihine kadar teslim ediniz`"></span>
                    <span v-else v-html="`Başvuru belgelerini komisyona <br> ` + this.belgeTeslimTarihi + ` tarihine kadar teslim etmediniz. Teslim süreniz geçmiştir`"></span>
                </v-list-item-content>
            </v-list-item>

            <template v-if="this.basvuru.islem_id>=2">
                <v-divider></v-divider>
                <v-list-item>
                    <v-list-item-content>
                        <span class="blue-grey--text" v-html="`Bitiş belgeleri ` + this.basvuru.bitis_belge_teslim"></span>
                    </v-list-item-content>
                    <v-list-item-content v-if="this.basvuru.bitis_belge_teslim===`bekleniyor`">
                        <span v-html="`Bitiş belgelerini komisyona <br> ` + this.bitis_start_date  + ` - ` + this.bitis_end_date + ` tarihleri arasında teslim ediniz.`"></span>
                    </v-list-item-content>
                </v-list-item>
            </template>

            <template v-if="this.basvuru.islem_id>=3">
                <v-divider></v-divider>
                <v-list-item>
                    <v-list-item-content>
                        <span class="blue-grey--text" v-html="`Mülakat durumunuz ` + this.basvuru.mulakat"></span>
                    </v-list-item-content>
                    <v-list-item-content v-if="this.basvuru.mulakat===`bekleniyor`">
                        <span v-html="`Mülakat tarihleriniz <br> ` + this.mulakat_start_date  + ` - ` + this.mulakat_end_date + ` tarihleri arasındadır. Lütfen bu tarihlerde mülakata katılınız.`"></span>
                    </v-list-item-content>
                </v-list-item>
            </template>

            <template v-if="basvuru.islem_id===4">
                <v-divider></v-divider>
                <v-list-item>
                    <v-list-item-content>
                        <span v-if="!basvuru.statu" class="blue-grey--text">Başvurunuz Red Edildi</span>
                        <span v-if="basvuru.statu" class="blue-grey--text">Başvurunuz Kabul Edildi</span>
                    </v-list-item-content>
                    <v-list-item-content v-if="this.basvuru.statu===1">
                        <span v-html="`Kabul edilen gün sayısı : ` + this.basvuru.kabul_edilen_gun_sayisi"></span>
                    </v-list-item-content>
                </v-list-item>
            </template>

            </v-card>
        </template>

        <template v-else>
            <v-stepper v-model="e6" vertical>
                <v-stepper-step :complete="e6 > 1" step="1" :rules="[() => (dilekceIndir === false)?false:true]">
                İşyeri Eğitimi
                <small>Başvuruyu Başlat</small>
                </v-stepper-step>

                <v-stepper-content step="1">
                <v-card flat outlined class="mb-12" min-height="200px">
                    <v-card-text>
                        <span class="text-caption">İşyeri Eğitimi Başvuru Dilekçesi ve Eklerini İndirdiniz mi?</span>
                        <v-radio-group v-model="dilekceIndir" column>
                            <v-radio label="Evet" :value=true></v-radio>
                            <v-radio label="Hayır" :value=false></v-radio>
                        </v-radio-group>
                        <v-alert
                            outlined
                            type="warning"
                            prominent
                            border="left"
                            v-if="dilekceIndir === false"
                            >
                            Lütfen isyeri başvuru dilekçesini ve eklerini indiriniz.
                        </v-alert>
                    </v-card-text>
                </v-card>
                <v-btn :disabled="!dilekceIndir" color="primary" @click="e6 = 2">Sonraki Adım</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 2" step="2" :rules="[() => (basvuruKabul === false)?false:true]">İşyeri Kabul</v-stepper-step>

                <v-stepper-content step="2">
                <v-card flat outlined class="mb-12 grey lighten-5" min-height="200px">
                    <v-card-text>
                        <span class="text-caption">İşyeri Eğitimi Başvurunuz İşyeri Tarafından Kabul Edildi mi?</span>
                        <v-radio-group v-model="basvuruKabul" column>
                            <v-radio label="Evet" :value=true></v-radio>
                            <v-radio label="Hayır" :value=false></v-radio>
                        </v-radio-group>
                        <v-alert
                            outlined
                            type="warning"
                            prominent
                            border="left"
                            v-if="basvuruKabul === false"
                            >
                            Bu durumda başvurunuz devam edemez.
                        </v-alert>
                    </v-card-text>
                </v-card>
                <v-btn :disabled="!basvuruKabul" color="primary" @click="e6 = 3">Sonraki Adım</v-btn>
                <v-btn text @click="e6 = 1">Önceki Adıma Dön</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 3" step="3" :rules="[() => (isyeriBasvuruKase === false)?false:true]">Başvuru Formu</v-stepper-step>

                <v-stepper-content step="3">
                <v-card flat outlined class="mb-12 grey lighten-5" min-height="200px">
                    <v-card-text>
                        <span class="text-caption">İşyeri başvuru formunuzun 3 nüshası da işyeri tarafından ıslak bir şekilde imzalandı ve kaşelendi mi?</span>
                        <v-radio-group v-model="isyeriBasvuruKase" column>
                            <v-radio label="Evet" :value=true></v-radio>
                            <v-radio label="Hayır" :value=false></v-radio>
                        </v-radio-group>
                        <v-alert
                            outlined
                            type="warning"
                            prominent
                            border="left"
                            v-if="isyeriBasvuruKase === false"
                            >
                            Lütfen isyeri başvuru formunuzun 3 nüshasını da iş yerine ıslak olarak imzalatınız ve kaşeletiniz
                        </v-alert>
                    </v-card-text>
                </v-card>
                <v-btn :disabled="!isyeriBasvuruKase" color="primary" @click="e6 = 4">Sonraki Adım</v-btn>
                <v-btn text @click="e6 = 2">Önceki Adıma Dön</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 4" step="4">İşyeri Bilgileri</v-stepper-step>
                <v-stepper-content step="4">
                    <v-card flat outlined class="mb-12 grey lighten-5" min-height="200px">
                        <v-card-text>
                            <v-form v-model="isyeriForm">
                                <v-row>
                                    <v-col cols="12" sm="6">
                                        <v-autocomplete
                                            color="blue-grey lighten-2"
                                            v-model="isyeri"
                                            :items="isyeriList"
                                            :loading="isLoading"
                                            :search-input.sync="searchFirma"
                                            hide-no-data
                                            hide-selected
                                            item-text="adi"
                                            item-value="id"
                                            label="Firma Adı"
                                            prepend-inner-icon="mdi-database-search"
                                            return-object
                                            hint="En Az 3 Harf Giriniz"
                                            persistent-hint
                                            outlined
                                            :disabled="firmaDurum"
                                        ></v-autocomplete>

                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-switch
                                        v-model="firmaDurum"
                                        label="Firma Bu Listede Yok İse İşaretleyiniz"
                                        ></v-switch>
                                    </v-col>
                                    <v-col v-if="firmaDurum" cols="12" sm="12">
                                        <v-text-field
                                            v-model="isyeri.adi"
                                            label="Firma Adı"
                                            outlined
                                            :rules="stringRules"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            v-model="isyeri.telefon"
                                            label="İşyerinin Telefonu"
                                            outlined
                                            :rules="phoneRules"
                                            v-mask="'#(###) ### ## ##'"
                                            :disabled="isyeri_input_disable"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            v-model="isyeri.email"
                                            label="İşyerinin Maili"
                                            outlined
                                            :rules="emailRules"
                                            :disabled="isyeri_input_disable"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-card-text>
                    </v-card>
                <v-btn :disabled="!isyeriForm" color="primary" @click="e6 = 5">Sonraki Adım</v-btn>
                <v-btn text @click="e6 = 3">Önceki Adıma Dön</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 5" step="5" :rules="[() => (isyeriTarihiKontrol === false)?false:true]">İşyeri Eğitimi Tarih</v-stepper-step>
                <v-stepper-content step="5">
                    <v-card flat outlined class="mb-12 grey lighten-5" min-height="200px">
                        <v-card-text>
                            <v-form>
                                <v-row>
                                    <v-col cols="12" sm="6">
                                        <v-menu
                                            v-model="menu"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="290px"
                                            >
                                                <template v-slot:activator="{ on }">
                                                <v-text-field
                                                    v-model="baslamaTarihi"
                                                    label="Başlangıç Tarihi"
                                                    prepend-icon="mdi-calendar-blank-outline"
                                                    readonly
                                                    v-on="on"
                                                ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                    v-model="baslamaTarihi"
                                                    @input="menu = false"
                                                    locale="tr"
                                                    :min="nowDate"
                                                    :max="bitisTarihi"
                                                    >
                                                </v-date-picker>
                                        </v-menu>
                                    </v-col>
                                    <v-col cols="12" sm="6">
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
                                                    v-model="bitisTarihi"
                                                    label="Bitiş Tarihi"
                                                    prepend-icon="mdi-calendar-blank-outline"
                                                    readonly
                                                    v-on="on"
                                                ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                    v-model="bitisTarihi"
                                                    @input="menu2 = false"
                                                    locale="tr"
                                                    :min="baslamaTarihi"
                                                    >
                                                </v-date-picker>
                                        </v-menu>
                                    </v-col>

                                    <v-col cols="12" sm="6">
                                        <v-checkbox
                                            v-model="cumartesiCalisma"
                                            label="İşyeri Cumartesi günleri çalışıyor mu?"
                                            >
                                        </v-checkbox>
                                    </v-col>

                                    <v-col cols="12" sm="6">
                                        <v-btn @click="calculateDays" class="ma-2" color="success">
                                            <v-icon left>mdi-calculator</v-icon> Gün Hesapla
                                        </v-btn>
                                    </v-col>

                                    <v-alert
                                        outlined
                                        type="warning"
                                        prominent
                                        border="left"
                                        v-if="isyeriTarihiKontrol === false"
                                        >
                                        15 günün altında ve 72 günün üzerinde işyeri eğitimi yapamazsınız. Lütfen işyeri eğitimi tarihlerinizi güncelleyiniz.
                                    </v-alert>

                                    <v-alert
                                        outlined
                                        type="success"
                                        prominent
                                        border="left"
                                        v-if="isyeriTarihiKontrol === true"
                                        >
                                        İşyeri eğitimi gün sayınız {{ this.calcDays }}
                                    </v-alert>

                                </v-row>
                            </v-form>
                        </v-card-text>
                    </v-card>
                <v-btn :disabled="!isyeriTarihiKontrol" color="primary" @click="e6 = 6">Sonraki Adım</v-btn>
                <v-btn text @click="e6 = 4">Önceki Adıma Dön</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 6" step="6" :rules="[() => (saglikProvizyon === false)?false:true]">Sağlık Provizyon Belgesi</v-stepper-step>
                <v-stepper-content step="6">
                <v-card flat outlined class="mb-12 grey lighten-5" min-height="200px">
                    <v-card-text>
                        <span class="text-caption">Sağlık provizyon belgenizi aldınız mı?</span>
                        <v-radio-group v-model="saglikProvizyon" column>
                            <v-radio label="Evet" :value=true></v-radio>
                            <v-radio label="Hayır" :value=false></v-radio>
                        </v-radio-group>
                        <v-alert
                            outlined
                            type="warning"
                            prominent
                            border="left"
                            v-if="saglikProvizyon === false"
                            >
                            Lütfen öğrenci belgesini ile birlikte Sosyal Güvenlik Kurumundan veya e-devlet aracılığı ile alınız.
                        </v-alert>
                    </v-card-text>
                </v-card>
                <v-btn :disabled="!saglikProvizyon" color="primary" @click="e6 = 7">Sonraki Adım</v-btn>
                <v-btn text @click="e6 = 5">Önceki Adıma Dön</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 7" step="7" :rules="[() => (saglikYardimi === false)?false:true]">Sağlık Yardımı Belgesi</v-stepper-step>
                <v-stepper-content step="7">
                <v-card flat outlined class="mb-12 grey lighten-5" min-height="200px">
                    <v-card-text>
                        <span class="text-caption">Aile Sağlık Yardımı Sorgulama  belgenizi aldınız mı?</span>
                        <v-radio-group v-model="saglikYardimi" column>
                            <v-radio label="Evet" :value=true></v-radio>
                            <v-radio label="Hayır" :value=false></v-radio>
                        </v-radio-group>
                        <v-alert
                            outlined
                            type="warning"
                            prominent
                            border="left"
                            v-if="saglikYardimi === false"
                            >
                            Başvuru evrakları sekmesinden indiriniz.
                        </v-alert>
                    </v-card-text>
                </v-card>
                <v-btn :disabled="!saglikYardimi" color="primary" @click="e6 = 8">Sonraki Adım</v-btn>
                <v-btn text @click="e6 = 6">Önceki Adıma Dön</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 8" step="8" :rules="[() => (IsgTemelEgitim === false)?false:true]">ISG Temel Eğitim Sertifikası</v-stepper-step>
                <v-stepper-content step="8">
                <v-card flat outlined class="mb-12 grey lighten-5" min-height="200px">
                    <v-card-text>
                        <span class="text-caption">ISG Temel Eğitim Sertifikanız var mı?</span>
                        <v-radio-group v-model="IsgTemelEgitim" column>
                            <v-radio label="Evet" :value=true></v-radio>
                            <v-radio label="Hayır" :value=false></v-radio>
                        </v-radio-group>
                        <v-alert
                            outlined
                            type="warning"
                            prominent
                            border="left"
                            v-if="IsgTemelEgitim === false"
                            >
                            Lütfen bölüm sekreterliğine uğrayarak İş Sağlığı ve Güvenliği
                            Eğitimine katılmak için başvuru yapınız ve isyeri eğitimine
                            başlamadan önve ISG Temel Eğitim Sertifikanızı alınız.
                        </v-alert>
                    </v-card-text>
                </v-card>
                <v-btn :disabled="!IsgTemelEgitim" color="primary" @click="basvuruYap">Başvurumu Kontrol Et</v-btn>
                <v-btn text @click="e6 = 7">Önceki Adıma Dön</v-btn>
                <v-btn text @click="e6 = 1">Başa Dön</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 9" step="9" :rules="[() => (EvrakTeslim === false)?false:true]">İşyeri Eğitimi Evrak Teslim</v-stepper-step>
                <v-stepper-content step="9">
                <v-card flat outlined class="mb-12 grey lighten-5" min-height="200px">
                    <v-card-text>
                        <v-alert
                            outlined
                            type="success"
                            prominent
                            border="left"
                            v-if="EvrakTeslim === true"
                            >
                            İşyeri eğitimi başvuru belgelerini en geç {{ belgeTeslimTarihi }} tarihine kadar staj komisyonuna teslim ediniz.
                            Evraklarınız staj komisyonu tarafından incelenecektir.
                            Başvurunuz uygun görülürse tarafınıza bilgilendirme maili gönderilecektir.
                            Sonrasında sisteme tekrar girdiğinizde bir sonraki adıma yönlendirileceksiniz.
                        </v-alert>
                    </v-card-text>
                </v-card>
                </v-stepper-content>

            </v-stepper>
        </template>



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
import moment from 'moment';
import business from 'moment-business-days';
export default {
    data () {
      return {
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
        EvrakTeslim: true,
        belgeTeslimTarihi : null,
        snackbarMessage: 'Bir sorun oluştu',
        snackbar: false,
        color: 'general',
        isLoading: false,
        searchFirma: null,
        isyeri_input_disable: false,
        firmaDurum: false,
        basvuru_start_date: '',
        basvuru_end_date: '',
        bitis_start_date: '',
        bitis_end_date: '',
        mulakat_start_date: '',
        mulakat_end_date: '',
        nowDate: new Date().toISOString().slice(0,10),
        basvuruTeslimDurum: true,
      }
    },
    computed: {
    },
    watch: {
      firmaDurum: function(newVal, previousVal) {
          this.isyeriList = [];
          this.isyeriListClear();
      },
      "isyeri.id": function(newVal, previousVal) {
        if (newVal>0) {
            this.isyeri_input_disable = true;
        }
        else {
            this.isyeri_input_disable = false;
            this.firmaDurum = true;
        }

      },
      searchFirma (val) {
        // Items have already been loaded
        //if (this.isyeriList.length > 0) return;

        if ((!val)||(val.length !== 3)) return;

        // Items have already been requested
        if (this.isLoading) return;

        this.isLoading = true;

        // Lazily load input items
        api.get('/isyeriegitimi/basvuru/firmalar?filter[adi]=' + val)
          .then(response => {
              if (response.data.success) {
                  this.isyeriList = response.data.data.firmalar;
                  this.isyeriListClear();
              }
          })
          .catch(err => {
            console.log(err)
          })
          .finally(() => (this.isLoading = false))
      },
      baslamaTarihi: function(newVal, previousVal) {
          this.isyeriTarihiKontrol = null;
      },
      bitisTarihi: function(newVal, previousVal) {
          this.isyeriTarihiKontrol = null;
      },
    },
    created () {
        this.getControl();
        this.getDates();
    },
    methods: {
        getControl () {
            api.get('/isyeriegitimi/basvuru')
                .then(response => {
                    if (response.data.success) {
                        this.basvuru = response.data.data;
                        this.controlFuntion();
                    }
                })
                .catch(err => {
                    this.color = 'info';
                    this.snackbar = true;
                    this.snackbarMessage = err.response.data.message;
                });
        },
        controlFuntion(){
            if (this.basvuru.id) {
                if (this.basvuru.tamamlanan.statu==='tamamlandı') {
                    this.basvuruControl = 2;
                }
                else {
                    this.basvuruControl = 1;
                    this.belgeTeslimTarihi = moment(this.basvuru.baslangic_tarihi).subtract(30, 'days').format("YYYY-MM-DD");
                    moment(this.belgeTeslimTarihi).isBefore(moment(), "day")?this.basvuruTeslimDurum=false:this.basvuruTeslimDurum=true;
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
        },
        getDates () {
            api.get('/isyeriegitimi/islem-tarihleri')
                .then(response => {
                    if (response.data.success) {
                        this.basvuru_start_date = response.data.data.basvuru_tarih.islemtarih.baslangic_tarihi;
                        this.basvuru_end_date = response.data.data.basvuru_tarih.islemtarih.bitis_tarihi;

                        this.bitis_start_date = response.data.data.bitis_tarih.islemtarih.baslangic_tarihi;
                        this.bitis_end_date = response.data.data.bitis_tarih.islemtarih.bitis_tarihi;

                        this.mulakat_start_date = response.data.data.mulakat_tarih.islemtarih.baslangic_tarihi;
                        this.mulakat_end_date = response.data.data.mulakat_tarih.islemtarih.bitis_tarihi;
                    }
                }
            );
        },
        calculateDays() {
            if (this.cumartesiCalisma) {
                business.updateLocale('us', {
                    workingWeekdays: [1, 2, 3, 4, 5, 6]
                });
            }
            else {
                business.updateLocale('us', {
                    workingWeekdays: [1, 2, 3, 4, 5]
                });
            }

            this.calcDays = business(this.bitisTarihi, "YYYY-MM-DD").businessDiff(business(this.baslamaTarihi, "YYYY-MM-DD"));
            if (business(this.bitisTarihi, "YYYY-MM-DD").isBusinessDay()) this.calcDays++;

            console.log('toplam iş günü : ' + this.calcDays);

            if ( (this.calcDays>=15) && (this.calcDays<=72) )
                this.isyeriTarihiKontrol = true;
            else
                this.isyeriTarihiKontrol = false;

        },
        basvuruYap(){
            let requestBody = {
                baslangic_tarihi: this.baslamaTarihi,
                bitis_tarihi: this.bitisTarihi,
                firma: {
                    id: this.isyeri.id,
                    adi: this.isyeri.adi,
                    telefon: this.isyeri.telefon,
                    email: this.isyeri.email,
                },
                cumartesi: this.cumartesiCalisma
            }
            /*let requestBody = {
                baslangic_tarihi: "2020-12-20",
                bitis_tarihi: "2021-01-30",
                firma: {
                    id: null,
                    adi: "işyeri adı yeni",
                    telefon: "5(345) 435 35 35",
                    email: "x@deneme.com",
                },
                cumartesi: this.cumartesiCalisma
            }*/
            api.post('/isyeriegitimi/basvuru', JSON.stringify(requestBody))
                .then(response => {
                    if (response.data.success) {
                        this.basvuru = response.data.data;
                        this.belgeTeslimTarihi = moment(this.basvuru.baslangic_tarihi).subtract(30, 'days').format("YYYY-MM-DD");
                        this.EvrakTeslim = true;
                        this.e6 = 9;
                    }
                })
                .catch(err => {
                    this.color = 'error';
                    this.snackbar = true;
                    this.snackbarMessage = err.response.data.message;
                });
        },

        deleteItem () {
                if (confirm('Bu başvuruyı silmek istediğinize emin misiniz?')){
                    this.loader = true;

                    api.delete('/isyeriegitimi/basvuru/' + this.basvuru.id).then(response => {
                        if (response.data.success) {
                            setTimeout(() => {
                                this.loader = false;
                                this.basvuru = null;
                                this.color = 'success';
                                this.snackbar = true;
                                this.snackbarMessage = "Başvuru Başarıyla Silindi";
                                this.basvuruControl = 0;
                            }, 1000);
                        }
                    })
                    .catch(err => {
                        this.color = 'error';
                        this.snackbar = true;
                        this.snackbarMessage = 'Bu işlemi gerçekleştiremezsiniz.';
                    });
                }
         },
         isyeriListClear(){
            this.isyeri.id = null;
            this.isyeri.adi = null;
            this.isyeri.telefon = null;
            this.isyeri.email = null;
         }

    },
}
</script>

<style>

</style>
