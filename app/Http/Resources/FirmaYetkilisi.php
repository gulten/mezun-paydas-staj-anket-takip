<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FirmaYetkilisi extends JsonResource
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
            'telefon' => is_object($this->firmayetkilisi) ? $this->firmayetkilisi->telefon : "",
            'firma_id' => is_object($this->firmayetkilisi) ? $this->firmayetkilisi->id : "",
            'firma' => is_object($this->firmayetkilisi) ? $this->firmayetkilisi->firma : "",
            'email' => $this->email,
            'status' => $this->status
        ];
    }
}
