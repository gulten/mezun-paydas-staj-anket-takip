<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnketSoru extends JsonResource
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
            'soru_tipi' => $this->soru_tipi,
            'soru' => $this->soru,
            'detay' => json_decode($this->detay),
            'required' => $this->required
        ];
    }
}
