<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BolumDersleri extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sinif' => $this->sinif,
            'donem' => $this->donem,
            'durum' => $this->durum,
            'ders_kodu' => $this->ders_kodu,
            'ders_adi' => $this->ders_adi,
            'haftalik_ders_saati' => $this->haftalik_ders_saati,
            'amac' => $this->amac,
            'icerik' => $this->icerik,
        ];
    }
}
