<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class IslemTarih extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'islem_id', 'baslangic_tarihi', 'bitis_tarihi'
    ];
    protected $table = "islem_tarihleri";

    protected $casts = [
    'baslangic_tarihi'  => 'date:Y-m-d',
    'bitis_tarihi'  => 'date:Y-m-d',
    ];

}
