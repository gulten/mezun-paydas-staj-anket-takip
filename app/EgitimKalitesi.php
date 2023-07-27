<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EgitimKalitesi extends Model
{
    use SoftDeletes;

    protected $table = "bolum_egitim_kalitesi";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
