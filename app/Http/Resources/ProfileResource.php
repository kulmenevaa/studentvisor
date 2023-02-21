<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'surname'       => $this->surname,
            'name'          => $this->name,
            'patronymic'    => $this->patronymic,
            'email'         => $this->email,
            'settings'      => $this->settings
        ];
    }
}
