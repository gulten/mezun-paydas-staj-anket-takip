<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Ogrenci extends JsonResource
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
            'ogrenci_no' => isset($this->ogrenci->ogrenci_no) ? $this->ogrenci->ogrenci_no : "",
            'name' => $this->name,
            'telefon' => isset($this->ogrenci->telefon) ? $this->ogrenci->telefon : "",
            'email' => $this->email,
            'status' => $this->status,
            'kayit_sekli' => isset($this->ogrenci->kayit_sekli) ? $this->ogrenci->kayit_sekli : "",
            'kayit_yili' => isset($this->ogrenci->kayit_yili) ? $this->ogrenci->kayit_yili : "",
            'adres' => isset($this->ogrenci->adres) ? $this->ogrenci->adres : ""
        ];
    }
}
