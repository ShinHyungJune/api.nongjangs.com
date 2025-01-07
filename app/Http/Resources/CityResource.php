<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\City */
class CityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'order' => $this->order,
            "counties" => CountyResource::collection($this->counties()->orderBy('order', 'asc')->get()),
        ];
    }
}
