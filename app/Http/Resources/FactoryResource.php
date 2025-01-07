<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Factory */
class FactoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,

            'city_id' => $this->city_id,
            'county_id' => $this->county_id,

            'county' => CountyResource::make($this->county),
        ];
    }
}
