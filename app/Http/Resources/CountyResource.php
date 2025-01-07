<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\County */
class CountyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'order' => $this->order,

            'city_id' => $this->city_id,
            'city' => $this->city ? CityResource::make($this->city) : '',
        ];
    }
}
