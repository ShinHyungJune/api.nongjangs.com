<?php

namespace App\Http\Resources;

use App\Models\CouponGroup;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Grade */
class GradeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'img' => $this->img ?? '',
            'level' => $this->level,
            'title' => $this->title,
            'ratio_refund' => $this->ratio_refund,
            'min_count_package' => $this->min_count_pacakge,
            'min_price' => $this->min_price,
            'couponGroup' => $this->couponGroup ? CouponGroupResource::make($this->couponGroup) : '',
        ];
    }
}