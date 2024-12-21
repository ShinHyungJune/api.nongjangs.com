<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CouponGroup */
class CouponGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'ratio_discount' => $this->ratio_discount,
            'duration' => $this->duration,
            'created_at' => $this->created_at,
        ];
    }
}
