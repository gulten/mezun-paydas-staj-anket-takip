<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OgretimElemani extends JsonResource
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
            'id' => $this->user->id,
            'name' => $this->user->name,
            'unvan' => $this->unvan,
            'cep_telefonu' => $this->cep_telefonu,
            'is_telefonu' => $this->is_telefonu,
            'email' => $this->user->email,
            'status' => $this->user->status
        ];
    }
}
