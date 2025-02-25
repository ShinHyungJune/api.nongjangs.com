<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Farm */
class FarmResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,

            'city_id' => $this->city_id,
            'county_id' => $this->county_id,

            'county' => CountyResource::make($this->county),

            'format_created_at' => Carbon::make($this->created_at)->format('y-m-d H:i'),
        ];
    }
}
