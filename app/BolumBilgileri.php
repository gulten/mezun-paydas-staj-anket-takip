<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BolumBilgileri extends Model
{
    protected $table = 'bolum_bilgileri';
    protected $fillable = [
        'universite_adi', 'fakulte_adi', 'adi', 'kurulus_yili', 'faaliyet_baslangic_tarihi', 'adres', 'telefon', 'email'
    ];
}
