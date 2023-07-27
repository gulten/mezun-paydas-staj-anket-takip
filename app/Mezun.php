<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mezun extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $dates = ['deleted_at'];
    protected $table = "mezunlar";
    protected $fillable = [
        'user_id', 'telefon', 'mezuniyet_tarihi', 'mezuniyet_suresi'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
