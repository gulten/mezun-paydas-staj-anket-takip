<?php

use App\BolumBilgileri;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BolumBilgileriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BolumBilgileri::create([
            'universite_adi' => 'Karadeniz Teknik Üniversitesi',
            'fakulte_adi' => 'Of Teknoloji Fakültesi',
            'adi' => 'Yazılım Mühendisliği',
            'kurulus_yili' => Carbon::parse('2011'),
            'faaliyet_baslangic_tarihi' => Carbon::parse('2014'),
            'adres' => 'KTÜ Of Teknoloji Fakültesi   Çamlı Mahallesi Hacı Mehmet Baheddin Ulusoy Cad. No:144',
            'telefon' => '4627717250',
            'email' => 'ofteknof@ktu.edu.tr'
        ]);
    }
}
