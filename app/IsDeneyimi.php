<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IsDeneyimi extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $timestamps = false;
    protected $table = "is_deneyimi";

    public function user(){
        return $this->belongsTo(User::class);
    }
}
