<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnketUser extends JsonResource
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
            'id' => $this->anket->id,
            'ad' => $this->anket->ad,
            'user' => $this->user->name,
        ];
        return $items;
    }
}
