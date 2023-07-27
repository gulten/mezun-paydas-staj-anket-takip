<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirmaYetkilisi extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $dates = ['deleted_at'];
    protected $table = "firma_yetkilisi";
    protected $fillable = [
        'user_id', 'telefon'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function firma()
    {
        return $this->belongsTo(Firma::class);
    }
}
