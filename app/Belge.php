<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Belge extends Model
{
    public $timestamps = true;
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'belge_id','belge_adi', 'belge_yolu', 'belge_aciklama', 'status'
    ];
    protected $table = "belgeler";

    public function getBelgeYoluAttribute($value)
    {
        return config('global.upload') .  $value;
    }

    public function belgeTipi()
    {
        return $this->belongsTo(BelgeTip::class);
    }
}
