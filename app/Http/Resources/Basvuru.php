<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Basvuru extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $items = [
            'id' => $this->id,
            'baslangic_tarihi' => $this->baslangic_tarihi,
            'islem_id' => $this->islem_id,
            'bitis_tarihi' => $this->bitis_tarihi,
            'cumartesi' => $this->cumartesi,
            'basvuru_belge_teslim' => $this->basvuru_belge_teslim,
            'bitis_belge_teslim' => $this->bitis_belge_teslim,
            'mulakat' => $this->mulakat,
            'statu' => $this->statu,
            'kabul_edilen_gun_sayisi' => $this->kabul_edilen_gun_sayisi,
        ];
        if ($this->relationLoaded('firma')) {
            $items['firma'] = new Firma($this->firma);
        }
        if ($this->relationLoaded('tamamlanan')) {
            $items['tamamlanan'] = new Tamamlanan($this->tamamlanan);
        }
        return $items;
    }
}
