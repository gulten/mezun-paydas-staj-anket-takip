<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Firma extends JsonResource
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
            'adi' => $this->adi,
            'telefon' => $this->telefon,
            'email' => $this->email
        ];
    }
}
