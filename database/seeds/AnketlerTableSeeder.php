<?php

use App\Anket;
use App\AnketSoru;
use Illuminate\Database\Seeder;

class AnketlerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $anket = Anket::create([
            'ad' => 'Mezun Anketi'
        ]);
        //1
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'Adınız Soyadınız',
            'required' => true,
            'sira' => 1
        ]);

        //2
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'Mezuniyet Yılınız',
            'required' => true,
            'sira' => 2
        ]);
        //3
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'E-Postanız',
            'required' => true,
            'sira' => 3
        ]);
        //4
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'Telefon Numaranız',
            'required' => false,
            'sira' => 4
        ]);
        //5
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'İş Yeri Adresiniz',
            'required' => false,
            'sira' => 5
        ]);
        //6
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRadio',
            'soru' => 'Çalıştığınız Kurum',
            'detay' => json_encode([
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Kamu'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Özel Sektör'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Kendi İşi'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'İşsiz'
                ],
            ]),
            'required' => false,
            'sira' => 6
        ]);
        //7
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'Kurum/Firma Adı',
            'required' => false,
            'sira' => 7
        ]);
        //8
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRadio',
            'soru' => 'Çalıştığınız Sektör',
            'detay' => json_encode([
                [
                    'tip' => 'VRadio',
                    'secenek' => 'İnşaat'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Tesisat'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Enerji'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Makina İmalat'
                ],
                [
                    'tip' => 'VTextField',
                    'secenek' => 'Diğer'
                ],
            ]),
            'required' => false,
            'sira' => 8
        ]);
        //9
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRadio',
            'soru' => 'Çalıştığınız Alan',
            'detay' => json_encode([
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Akademisyen'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Yönetim'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'İmalat'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Danışmanlık'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Arge'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Bakım-Onarım'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Kalite Kontrol'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'İnsan Kaynakları'
                ],
                [
                    'tip' => 'VTextField',
                    'secenek' => 'Diğer'
                ],
            ]),
            'required' => false,
            'sira' => 9
        ]);
        //10
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRadio',
            'soru' => 'İşe Alımda Okuduğunuz Üniversite Adı Size Avantaj Sağladı mı?',
            'detay' => json_encode([
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Evet'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Hayır'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Kısmen'
                ],
            ]),
            'required' => false,
            'sira' => 10
        ]);
        //11
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRadio',
            'soru' => 'KTÜ Mezunu olmanın piyasada avantajlı bir imaj yarattığını düşünüyor musunuz?',
            'detay' => json_encode([
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Evet'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Hayır'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Kısmen'
                ],
            ]),
            'required' => false,
            'sira' => 11
        ]);
        //12
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRadio',
            'soru' => 'Lisansüstü dereceniz var mı?',
            'detay' => json_encode([
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Yüksek Lisans Mezun'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Doktora Mezun'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Yüksek Lisans Devam Ediyor'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Doktora Devam Ediyor'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Yok'
                ],
            ]),
            'required' => true,
            'sira' => 12
        ]);
        //13
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'Lisansüstü yaptığınız veya yapmakta olduğunuz üniversite ve bölümünüz nedir?',
            'required' => false,
            'sira' => 13
        ]);
        //14
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRadio',
            'soru' => 'Yabancı dil bilginizi hangi seviyede görüyorsunuz?',
            'detay' => json_encode([
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Çok iyi'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'İyi'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Orta'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Kötü'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Çok'
                ],
            ]),
            'required' => true,
            'sira' => 14
        ]);
        //15
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRadio',
            'soru' => 'Çalışıyor olduğunuz işinizden memnun musunuz?',
            'detay' => json_encode([
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Çok Memnunum'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Memnunum'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Memnun Değilim'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Hiç Memnun Değilim'
                ],
            ]),
            'required' => false,
            'sira' => 15
        ]);
        //16
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRadio',
            'soru' => 'Mühendisler Odasına kayıtlı mısınız?',
            'detay' => json_encode([
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Evet'
                ],
                [
                    'tip' => 'VRadio',
                    'secenek' => 'Hayır'
                ],
            ]),
            'required' => true,
            'sira' => 16
        ]);
        //17
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextarea',
            'soru' => 'Bölüme katkıda bulunabileceğini düşündüğünüz bir tavsiyeniz var mı?',
            'required' => true,
            'sira' => 17
        ]);

        $anket = Anket::create([
            'ad' => 'Akademik Personel Anketi'
        ]);
        //1
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => 'Çalıştığım ortamın temizliğinden memnuniyetiniz',
            'required' => false,
            'sira' => 1
        ]);
        //2
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => 'Güvenlik tedbirlerinin yeterliliğinden (yangın, deprem, güvenlik hizmetleri… vb.) memnuniyetiniz',
            'required' => false,
            'sira' => 2
        ]);
        //3
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => 'Bilgisayar, yazıcı, fotokopi vb. araç-gereçlerin temininden memnuniyetiniz',
            'required' => false,
            'sira' => 3
        ]);
        //4
        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => 'Internet hizmetlerinden memnuniyetiniz',
            'required' => false,
            'sira' => 4
        ]);

        $anket = Anket::create([
            'ad' => 'Öğrenci Memnuniyet Anketi'
        ]);

        $anket = Anket::create([
            'ad' => 'Personel Memnuniyet Anketi'
        ]);

        $anket = Anket::create([
            'ad' => 'Yeni Gelen Öğrenci Anketi'
        ]);

        //İşyeri Eğitimi Öğrenci Anketi
        $anket = Anket::create([
            'ad' => 'İşyeri Eğitimi Öğrenci Anketi',
            'amac' => 'Sevgili Öğrenciler; Bu anketin amacı, işyeri eğitiminin niteliğini sistemli bir biçimde geliştirmek için görüşlerinizden yararlanmaktır. Yaptığınız işyeri eğitimini aşağıdaki kriterler açısından değerlendirmeniz istenmektedir. Değerlendirmenizin kendi kişisel gözlem ve algılarınıza dayanıyor olması bu verilerin geçerliliği ve güvenirliliği açısından çok önemlidir. Buradaki sorulara verdiğiniz cevaplar sizin işyeri eğitiminizin değerlendirilmesi sırasında dikkate alınmayacaktır. Verdiğiniz katkılar için teşekkür ederiz.'
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => 'Fakültede almış olduğunuz eğitimin, işyeri eğitimi için yeterlilik düzeyini değerlendiriniz.',
            'required' => false,
            'sira' => 1
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => 'İşyeri Eğitimini gerçekleştirdiğiniz kuruluş/işletmenin yeterlilik düzeyini değerlendiriniz.',
            'required' => false,
            'sira' => 2
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => 'İşyeri Eğitimi yetkilisinin eğitiminizle ilgilenme düzeyini değerlendiriniz.',
            'required' => false,
            'sira' => 3
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => 'İşyeri Eğitimi süresinin yeterlilik düzeyini değerlendiriniz.',
            'required' => false,
            'sira' => 4
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => 'Sorumlu öğretim üyesi ile görüşme sıklığını değerlendiriniz.',
            'required' => false,
            'sira' => 4
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'İşyeri Eğitiminde okulda gördüğünüz konular dışında hangi bilgilere/programlara ihtiyaç duydunuz?',
            'required' => false,
            'sira' => 5
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'İşyeri Eğitimi esnasında karşılaştığınız güçlükleri belirtiniz.',
            'required' => false,
            'sira' => 5
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'İşyeri Eğitimi, Mesleğinizin hangi alt alanları açısından bilgi ve becerinizi artırmaya katkı sağlamıştır.',
            'required' => false,
            'sira' => 5
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'İşyeri Eğitimi süresince işletmeye/kuruluşa yapmış olduğunuz en önemli katkıyı belirtiniz.',
            'required' => false,
            'sira' => 5
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'İşyeri Eğitimine gidecek arkadaşlarınıza önerileriniz nelerdir?',
            'required' => false,
            'sira' => 5
        ]);

        //İşyeri Eğitimi İşyeri Anketi
        $anket = Anket::create([
            'ad' => 'İşyeri Eğitimi İşyeri Anketi',
            'amac' => 'Karadeniz Teknik Üniversitesi Teknoloji Fakültesi Mühendislik Programlarında öğrencilerin bir yarıyıl işletmelerde yapmış oldukları işyeri eğitimi uygulamasının etkinliğini ölçmek, İşyeri Eğitimi uygulaması hususunda ortaya çıkabilecek aksaklıkları gidermek, Mühendislik Eğitiminin kalitesini artırmak ve iş piyasasının beklentilerini karşılayabilecek mühendislerin yetişmesini sağlamak amacıyla durum analizi yapmaktır. Burada verilen bilgiler üçüncü şahıslar ve başka kurum ya da kuruluşlar ile paylaşılmayacaktır.'
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => ' İşyeri Eğitimi uygulaması ile kurumunuzdaki faaliyetlerinize uygun mühendis profili ne oranda yetiştirilebilir?',
            'required' => false,
            'sira' => 1
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => 'İşyeri Eğitimi için uygulanan süre yeterli midir?',
            'required' => false,
            'sira' => 1
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VRating',
            'soru' => 'Mühendis adayının fakültede aldığı eğitim yeterli midir?',
            'required' => false,
            'sira' => 1
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'İşyeri Eğitimi uygulaması ile ilgili öğrenciden beklentilerinizi ve önerilerinizi belirtiniz.',
            'required' => false,
            'sira' => 5
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'İşyeri Eğitimi uygulaması ile ilgili Fakültemizden beklentilerinizi ve önerilerinizi belirtiniz.',
            'required' => false,
            'sira' => 5
        ]);

        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'İşyeri Eğitimi uygulaması ile ilgili şimdi veya ileride karşılaşılabilecek sorunlar/engeller hakkında düşüncelerinizi paylaşır mısınız?',
            'required' => false,
            'sira' => 5
        ]);


        AnketSoru::create([
            'anket_id' => $anket->id,
            'soru_tipi' => 'VTextField',
            'soru' => 'Varsa diğer görüş ve önerilerinizi belirtiniz.',
            'required' => false,
            'sira' => 5
        ]);


    }
}
