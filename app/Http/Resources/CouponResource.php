<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Coupon */
class CouponResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,

            'user_id' => $this->user_id,
            'coupon_group_id' => $this->coupon_group_id,
            'order_id' => $this->order_id,

            'couponGroup' => new CouponGroupResource($this->whenLoaded('couponGroup')),
        ];
    }
}
