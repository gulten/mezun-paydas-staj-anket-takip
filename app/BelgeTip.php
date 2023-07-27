<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BelgeTip extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'ad', 'teslim_tarihi'
    ];

    protected $table = "belge_tipleri";

    public function belgeler()
    {
        return $this->hasMany(Belge::class, 'id', 'belge_id');
    }
}
