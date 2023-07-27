<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YeniDersOneri extends Model
{
    use SoftDeletes;

    protected $table = "bolum_yeniders_oneri";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
