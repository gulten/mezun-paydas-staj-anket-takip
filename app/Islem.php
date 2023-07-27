<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Islem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'islem_adi'
    ];
    protected $table = "islemler";

    public function islemtarih()
    {
        return $this->hasOne(IslemTarih::class);
    }

}
