<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnketUser extends Model
{
    protected $fillable = [
        'user_id', 'anket_id'
    ];

    protected $table = "anket_user";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function anket()
    {
        return $this->belongsTo(Anket::class, 'anket_id');
    }

    public function anketsorular()
    {
        return $this->hasMany(AnketSoru::class, 'anket_id')->orderBy('sira');
    }
}
