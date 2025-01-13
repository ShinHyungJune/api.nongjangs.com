<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Coupon */
class CouponResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'user_id' => $this->user_id,
            'coupon_group_id' => $this->coupon_group_id,

            'use' => $this->use,

            'couponGroup' => new CouponGroupResource($this->whenLoaded('couponGroup')),
        ];
    }
}
