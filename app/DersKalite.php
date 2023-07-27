<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DersKalite extends Model
{
    use SoftDeletes;

    protected $table = "bolum_ders_kalite";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bolumders()
    {
        return $this->hasOne(BolumDersleri::class, 'id', 'ders_id');
    }
}
