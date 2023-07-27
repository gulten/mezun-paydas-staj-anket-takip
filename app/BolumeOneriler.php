<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BolumeOneriler extends Model
{
    use SoftDeletes;
    protected $table = "bolume_oneriler";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bolumders()
    {
        return $this->hasOne(BolumDersleri::class,'id','ders_id');
    }
}
