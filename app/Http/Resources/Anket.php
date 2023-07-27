<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Anket extends JsonResource
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
            'ad' => $this->ad,
        ];
        if ($this->relationLoaded('anketsorulari')) {
            $items['anket_sorular'] = AnketSoru::collection($this->anketsorulari);
        }
        return $items;
    }
}
