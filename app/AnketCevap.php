<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnketCevap extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'anket_id', 'user_id', 'soru_id', 'cevap'
    ];

    protected $table = "anket_cevaplari";

    public function anketsoru()
    {
        return $this->hasOne(AnketSoru::class, 'id', 'soru_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
