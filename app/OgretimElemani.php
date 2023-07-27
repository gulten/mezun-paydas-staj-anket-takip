<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgretimElemani extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $dates = ['deleted_at'];
    protected $table = "ogretim_elemanlari";
    protected $fillable = [
        'user_id', 'unvan', 'cep_telefonu', 'is_telefonu'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
