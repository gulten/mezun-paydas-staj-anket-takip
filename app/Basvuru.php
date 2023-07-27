<?php

namespace App;

use App\Firma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Api\Helpers\CalculateDatePeriod;
use App\Http\Controllers\Api\Helpers\CalculateBusinessDays;

class Basvuru extends Model
{
    use SoftDeletes, CalculateBusinessDays, CalculateDatePeriod;
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = "basvurular";
    protected $fillable = ['user_id', 'yetkili_id', 'islem_id', 'firma_id',
        'baslangic_tarihi', 'bitis_tarihi', 'cumartesi', 'basvuru_belge_teslim',
        'bitis_belge_teslim', 'mulakat', 'ogrenci_anket_id', 'yetkili_anket_id',
        'sicil_fisi_notu', 'dosya_notu', 'mulakat_notu', 'staj_gun_sayisi',
        'kabul_edilen_gun_sayisi', 'red_edilen_gun_sayisi', 'statu'
    ];

    public function getStatuAttribute()
    {
        return (int)$this->attributes['statu'];
    }

    public function scopeAccept($query)
    {
        return $query->where('basvuru_belge_teslim', 'teslim edildi');
    }

    public function scopeReject($query)
    {
        return $query->where('basvuru_belge_teslim', 'bekleniyor');
    }

    public function scopeAnket($query)
    {
        return $query->where('ogrenci_anket_id', '>', 0)
                    ->where('yetkili_anket_id', '>', 0);
    }

    public function user()
    {
        return $this->belongsTo(User::class)
                    ->with('ogrenci');
    }

    public function firma(){
        return $this->belongsTo(Firma::class);
    }

    public function firmayetkilisi()
    {
        return $this->belongsTo(User::class, 'yetkili_id');
    }


}
