<?php

use App\Belge;
use App\BelgeTip;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BelgelerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $belge = BelgeTip::create([
            'ad' => 'İşyeri Eğitimi Başvuru Belgesi',
            'teslim_tarihi' => Carbon::parse('2020-10-23'),
        ]);

        Belge::create([
            'belge_id' => $belge->id,
            'belge_adi' => 'İşyeri Başvuru Dilekçesi',
            'belge_yolu' => 'isyeri_6f6b5.docx',
            'belge_aciklama' => 'Kurum/Kuruluş işyerine ilk başvuruda verilecek',
            'status' => 'active'
        ]);

        Belge::create([
            'belge_id' => $belge->id,
            'belge_adi' => 'İşyeri Eğitimi Komisyon Yazısı',
            'belge_yolu' => 'isyeri_47a37.pdf',
            'belge_aciklama' => 'Kurum/Kuruluş işyerine verilecek',
            'status' => 'active'
        ]);

        Belge::create([
            'belge_id' => $belge->id,
            'belge_adi' => 'İşyeri Eğitimi Başvuru Formu',
            'belge_yolu' => 'isyeri_94ddc.pdf',
            'belge_aciklama' => 'İşyerine onaylatılacak ve komisyona verilecek',
            'status' => 'active'
        ]);

        Belge::create([
            'belge_id' => $belge->id,
            'belge_adi' => 'Aile Sağlık Sorgulama Belgesi',
            'belge_yolu' => 'isyeri_4c0ef.pdf',
            'belge_aciklama' => 'Komisyona verilecek',
            'status' => 'active'
        ]);

        Belge::create([
            'belge_id' => $belge->id,
            'belge_adi' => 'Öğrenci Bilgi Formu',
            'belge_yolu' => 'isyeri_05dcc.pdf',
            'belge_aciklama' => 'İşyeri eğitimi komisyonuna onaylatılıp komisyona verilecek',
            'status' => 'active'
        ]);

        //İşyeri Eğitimi Sonrası Teslim Edilecek Belgeler
        $belge = BelgeTip::create([
            'ad' => 'İşyeri Eğitimi Sonrası Teslim Edilecek Belgeler',
            'teslim_tarihi' => Carbon::parse('2020-12-23'),
        ]);

        Belge::create([
            'belge_id' => $belge->id,
            'belge_adi' => 'Devam Çizelgesi',
            'belge_yolu' => 'ofyazilim_5f960.pdf',
            'belge_aciklama' => 'İşyeri yetkilisi tarafından doldurulacak',
            'status' => 'active'
        ]);

        Belge::create([
            'belge_id' => $belge->id,
            'belge_adi' => 'İşyeri Eğitimi Rapor Şablonu',
            'belge_yolu' => 'ofyazilim_8650b.docx',
            'belge_aciklama' => 'Rapor oluşturulurken kullanılacak şablon',
            'status' => 'active'
        ]);

        Belge::create([
            'belge_id' => $belge->id,
            'belge_adi' => 'İşyeri Yetkilisi Değerlendirme Formu (Sicil fişi)',
            'belge_yolu' => 'ofyazilim_703f0.docx',
            'belge_aciklama' => 'İşyeri Yetkili Mühendisi tarafından doldurulup kapalı onaylı zarf ile komisyona gönderilecek',
            'status' => 'active'
        ]);

        Belge::create([
            'belge_id' => $belge->id,
            'belge_adi' => 'Öğrenci Anketi',
            'belge_aciklama' => 'Öğrenci tarafından doldurulup komisyona teslim edilecek, lütfen anket menüsünden formu doldurarak çıktısını alınız',
            'status' => 'active'
        ]);

        Belge::create([
            'belge_id' => $belge->id,
            'belge_adi' => 'İşyeri Yetkilisi Anketi',
            'belge_aciklama' => 'İşyeri Yetkili Mühendisi tarafından doldurulup komisyona gönderilecek, İşyeri Yetkili Mühendisi sisteme giriş yaparak doldurarak çıktısını alacaktır.',
            'status' => 'active'
        ]);



    }
}
