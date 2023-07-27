<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Komisyon extends JsonResource
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
            'roles' => $this->roles,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
        ];
    }
}
