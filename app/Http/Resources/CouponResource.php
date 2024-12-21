<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Coupon */
class CouponResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'ratio_discount' => $this->ratio_discount,
            'will_finished_at' => $this->will_finished_at ? Carbon::make($this->will_finished_at)->format('Y-m-d') : '',
        ];
    }
}
