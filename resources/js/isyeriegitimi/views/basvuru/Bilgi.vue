<template>
    <div>
        <v-card>
            <v-card-title>
                İşyeri Eğitimi Başvuru Süreci İçin Öğrencinin İzleyeceği Yol
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text class="pa-5">
            <v-timeline dense>
                <v-timeline-item
                    v-for="(item,i) in infoList"
                    :key="i"
                    color="red lighten-2"
                >
                    <span slot="opposite">{{ i++ }}</span>
                    <v-card class="elevation-2">
                    <v-card-title class="headline">{{ item.title }}</v-card-title>
                    <v-card-text>
                        {{ item.text }}
                    </v-card-text>
                    </v-card>
                </v-timeline-item>
            </v-timeline>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import moment from 'moment';
export default {
head: {
    title: 'Bilgi'
  },
    data() {
        return {
            title: 'Bilgi',
            bitis_start_date: '',
            bitis_end_date: '',
            mulakat_start_date: '',
            mulakat_end_date: '',
            belgeTeslimTarihi : null,
            start_date: null,
            end_date: null,
        }
    },
    computed: {
        infoList() {
            return [
                {
                    title: 'Başvuru Öncesi',
                    text: 'Başvuru dilekçesini ve eklerini indiriniz',
                },
                {
                    title: 'Başvuru Öncesi',
                    text: 'Firma başvurunuzu kabul ettiyse başvuru dilekçenizin 3 nüshasını ıslak imzalı ve kaşeli olacak şekilde onaylatınız',
                },
                {
                    title: 'Başvuru İçin',
                    text: 'Arayüz aracılığı ile başvurunuzu ' + this.start_date + ' - ' + this.end_date + ' tarihleri arasında gerçekleştiriniz. Başvurunuz minimum 15, maksimum 72 gün olabilir.',

                },
                {
                    title: 'Başvuru Belgeleri Teslim Öncesi',
                    text: 'Sağlık Provizyon Belgenizi Alınız. Lütfen öğrenci belgesini ile birlikte Sosyal Güvenlik Kurumundan veya e-devlet aracılığı ile alabilirsiniz.',
                },
                {
                    title: 'Başvuru Belgeleri Öncesi',
                    text: 'Başvuru için gerekli belgeleri arayüz aracılığı ile indirerek doldurunuz en son ' + this.belgeTeslimTarihi + '  tarihine kadar komisyona teslim ediniz.',
                },
                {
                    title: 'Başvuru Belge Teslimi Sırasında',
                    text: 'Başvuru belgelerinizin teslimi en geç işyeri eğitimine başlamadan 30 gün öncesidir. Başvuru belgelerinin teslimi esnasında komisyona firma yetkilisinin mail adresi bilgisini vermeyi unutmayınız. Yetkili sisteme kullanıcı olarak kaydedilmelidir.',
                },
                {
                    title: 'Başvuru Belge Teslimi Sonrasında',
                    text: 'Başvuru durumunuzu arayüz aracılığı ile takip etmelisiniz.',
                },
                {
                    title: 'İşyeri Eğitimine Başlamadan Önce',
                    text: 'İşe giriş çıkış bildirgeniz hazırlanmış olmalı. 0 (462) 377 3800 nolu telefonu arayarak KTÜ Sağlık ve Spor Daire Başkanlığı nın "Staj Birimi" ne  bilgi veriniz. Stajınıza başlamayınız.',
                },
                {
                    title: 'İşyeri Eğitimine Başlamadan Önce',
                    text: 'Staj sicil fişi ve staj dosyasını arayüz aracılığı ile indiriniz.',
                },
                {
                    title: 'İşyeri Eğitimi Bitiş',
                    text: 'Kendiniz ve firma yetkilisi arayüz aracılığı ile işyeri eğitimi değerlendirme ankentini doldurarak indirmelidir. Bitiş belgeleri sekmesinden gerekli açıklamaları takip ediniz.',
                },
                {
                    title: 'İşyeri Eğitimi Bitiş',
                    text: 'Kendiniz ve firma yetkilisi arayüz aracılığı ile işyeri eğitimi değerlendirme ankentini doldurarak indirmelidir. Bitiş belgeleri sekmesinden gerekli açıklamaları takip ediniz.',
                },
                {
                    title: 'İşyeri Eğitimi Bitiş',
                    text: 'Bitiş belgelerinizi ' + this.bitis_start_date + ' - ' + this.bitis_end_date + ' tarihleri arasında komisyona teslim ediniz. Mülakat bilgilerini arayüz aracılığı ile takip etmelisiniz.',
                },
                {
                    title: 'İşyeri Eğitimi Bitiş Mülakatı',
                    text: 'Mülakata ' + this.mulakat_start_date + ' - ' + this.mulakat_end_date + ' tarihleri arasında giriniz. Mülakat sonuçlarını arayüz aracılığı ile takip etmelisiniz.',
                }
            ]
        }
    },
    created () {
        this.getDates();
    },
    methods: {
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
    }
}
</script>

<style>

</style>
