<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anket extends Model
{
    public $timestamps = false;
    protected $table = "anketler";
    protected $fillable = [
        'ad'
    ];

    public function anketsorulari(){
        return $this->hasMany(AnketSoru::class)->orderBy('sira');
    }

    public function anketuser()
    {
        return $this->hasOne(AnketUser::class);
    }
}
