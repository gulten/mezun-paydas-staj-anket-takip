<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ogrenci extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $dates = ['deleted_at'];
    protected $table = "ogrenciler";
    protected $fillable = [
        'user_id', 'telefon', 'ogrenci_no', 'kayit_sekli', 'kayit_yili', 'adres'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
