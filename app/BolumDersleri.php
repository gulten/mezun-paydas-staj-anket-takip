<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BolumDersleri extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "bolum_dersleri";

    public function bolumeoneri()
    {
        return $this->belongsTo(BolumeOneriler::class,'id','ders_id');
    }

    public function dersgereklilik()
    {
        return $this->belongsTo(DersGereklilik::class, 'id', 'ders_id');
    }

    public function derskalite()
    {
        return $this->belongsTo(DersKalite::class, 'id', 'ders_id');
    }

}
