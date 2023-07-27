<?php

namespace App;

use Carbon\Carbon;

class IsyeriEgitimiBasvuru extends Basvuru
{
    protected $appends = ['basvuru_belge_tarihi'];

    public function scopeActive($query)
    {
        $tarih = Islem::where('adi', 'İşyeri Eğitimi Başvurusu')->with('islemtarih')->first();
        return $query->whereBetween('created_at', [$tarih->islemtarih->baslangic_tarihi, $tarih->islemtarih->bitis_tarihi]);
    }

    public function scopeBitis($query)
    {
        return $query->whereIn('islem_id', [2, 3, 4]);
    }

    public function scopeMulakat($query)
    {
        return $query->whereIn('islem_id', [3, 4]);
    }

    public function getBasvuruBelgeTarihiAttribute($value)
    {
        $now = Carbon::now();
        $day = Carbon::parse($this->baslangic_tarihi);
        $now->addDays(config('global.isyeri.basvuru'));

        if (($this->basvuru_belge_teslim === "teslim edildi") || ($day >= $now))
            return true;
        if(($this->basvuru_belge_teslim=== "bekleniyor")&&($day < $now))
            return false;

    }

    public function tamamlanan()
    {
        return $this->belongsTo(Tamamlanan::class, 'user_id', 'user_id')->withDefault([
            'statu' => 'tamamlanmadı',
        ])->where('islem_id', 4);
    }
}
