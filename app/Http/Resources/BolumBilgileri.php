<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BolumBilgileri extends JsonResource
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
            'universite_adi' => $this->universite_adi,
            'fakulte_adi' => $this->fakulte_adi,
            'adi' => $this->adi,
            'kurulus_yili' => $this->kurulus_yili,
            'faaliyet_baslangic_tarihi' => $this->faaliyet_baslangic_tarihi,
            'adres' => $this->adres,
            'telefon' => $this->telefon,
            'email' => $this->email
        ];
    }
}
