<?php

use App\Islem;
use Carbon\Carbon;
use App\IslemTarih;
use Illuminate\Database\Seeder;

class IslemTarihleriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $islem = Islem::create([
            'adi' => 'İşyeri Eğitimi Başvurusu',
        ]);
        IslemTarih::create([
            'islem_id' => $islem->id,
            'baslangic_tarihi' => Carbon::parse('2020-10-17'),
            'bitis_tarihi' => Carbon::parse('2020-12-23'),
        ]);

        $islem = Islem::create([
            'adi' => 'İşyeri Eğitimi Bitiş Evrakları Teslimi',
        ]);
        IslemTarih::create([
            'islem_id' => $islem->id,
            'baslangic_tarihi' => Carbon::parse('2021-03-17'),
            'bitis_tarihi' => Carbon::parse('2021-04-30'),
        ]);

        $islem = Islem::create([
            'adi' => 'İşyeri Eğitimi Mülakat',
        ]);
        IslemTarih::create([
            'islem_id' => $islem->id,
            'baslangic_tarihi' => Carbon::parse('2021-01-17'),
            'bitis_tarihi' => Carbon::parse('2021-02-30'),
        ]);

        $islem = Islem::create([
            'adi' => 'İşyeri Eğitimi Değerlendirme',
        ]);
    }
}
