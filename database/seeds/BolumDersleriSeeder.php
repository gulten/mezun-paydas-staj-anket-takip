<?php

use App\BolumDersleri;
use Illuminate\Database\Seeder;

class BolumDersleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BolumDersleri::create([
            'sinif' => '1',
            'donem' => 'Güz',
            'ders_kodu' => 'YDB1001',
            'ders_adi' => 'İngilizce - I',
            'icerik' => ''
        ]);

        BolumDersleri::create([
            'sinif' => '1',
            'donem' => 'Bahar',
            'ders_kodu' => 'YZM1001',
            'ders_adi' => 'Yazılım Mühendisliğine Giriş',
            'icerik' => ''
        ]);

        BolumDersleri::create([
            'sinif' => '3',
            'donem' => 'Güz',
            'ders_kodu' => 'YDB1002',
            'ders_adi' => 'Matematik',
            'icerik' => ''
        ]);
    }
}
