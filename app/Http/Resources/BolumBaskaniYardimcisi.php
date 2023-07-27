<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BolumBaskaniYardimcisi extends JsonResource
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
            'name' => $this->name,
            'telefon' => is_object($this->bolumbaskanyardimcisi) ? $this->bolumbaskanyardimcisi->telefon : "",
            'email' => $this->email,
            'status' => $this->status
        ];

    }
}
