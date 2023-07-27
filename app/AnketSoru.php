<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnketSoru extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $fillable = [
        'anket_id','soru', 'soru_tipi', 'detay', 'required', 'sira'
    ];

    protected $table = "anket_sorulari";

    public function anket(){
        $this->belongsTo(Anket::class);
    }

    public function anketcevap()
    {
        $this->belongsTo(AnketCevap::class);
    }
}
