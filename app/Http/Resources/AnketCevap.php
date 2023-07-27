<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnketCevap extends JsonResource
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
            'cevap' => $this->cevap,
        ];

        if ($this->relationLoaded('anketsoru')) {
            $items['soru'] = new AnketSoru($this->anketsoru);
        }
        return $items;

    }
}
