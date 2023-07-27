<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Mezun extends JsonResource
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
            'email' => $this->email,
            'status' => $this->status,
            'telefon' => is_object($this->mezun) ? $this->mezun->telefon : "",
            'mezuniyet_tarihi' => is_object($this->mezun) ? $this->mezun->mezuniyet_tarihi : "",
            'mezuniyet_suresi' => is_object($this->mezun) ? $this->mezun->mezuniyet_suresi : "",
        ];

    }
}
