<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BolumBaskaniYardimcisi extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $dates = ['deleted_at'];
    protected $table = 'bolum_baskani_yardimcilari';
    protected $fillable = [
        'user_id', 'telefon'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
