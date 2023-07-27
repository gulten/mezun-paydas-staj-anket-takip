<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Kullanici extends JsonResource
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
            'roles' => $this->roles,
            'email' => $this->email,
            'status' => $this->status
        ];

    }
}
