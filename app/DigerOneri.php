<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DigerOneri extends Model
{
    use SoftDeletes;

    protected $table = "bolum_diger_oneri";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
