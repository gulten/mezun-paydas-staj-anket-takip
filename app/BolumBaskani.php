<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BolumBaskani extends Model
{
    public $timestamps = false;
    protected $table = 'bolum_baskani';

    protected $fillable = [
        'user_id', 'telefon', 'email'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
