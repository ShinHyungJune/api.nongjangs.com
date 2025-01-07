<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Count */
class CountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sum_weight' => $this->sum_weight,
            'sum_store' => $this->sum_store,
        ];
    }
}
