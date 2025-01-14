<?php

namespace App\Http\Resources;

use App\Models\CouponGroup;
use Carbon\Carbon;
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

            'started_at' => $this->started_at,
            'format_started_at' => Carbon::make($this->started_at)->format('Y.m.d H:i'),
            'finished_at' => $this->finished_at,
            'format_finished_at' => Carbon::make($this->finished_at)->format('Y.m.d H:i'),

            'couponGroup' => $this->couponGroup ? CouponGroupResource::make($this->couponGroup) : '',
        ];
    }
}
