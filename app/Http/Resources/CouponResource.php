<?php

namespace App\Http\Resources;

use App\Models\CouponGroup;
use App\Models\Preset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Coupon */
class CouponResource extends JsonResource
{
    public function toArray($request)
    {
        $user = User::withTrashed()->find($this->user_id);

        return [
            'id' => $this->id,

            'user_id' => $this->user_id,
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'nickname' => $user->nickname,
            ] : '',
            'coupon_group_id' => $this->coupon_group_id,

            'use' => $this->use,

            'preset' => $this->preset ? PresetResource::make($this->preset) : '',

            'started_at' => $this->started_at,
            'format_started_at' => Carbon::make($this->started_at)->format('Y.m.d H:i'),
            'finished_at' => $this->finished_at,
            'format_finished_at' => Carbon::make($this->finished_at)->format('Y.m.d H:i'),

            'couponGroup' => $this->couponGroup ? CouponGroupResource::make($this->couponGroup) : '',
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d H:i'),
        ];
    }
}
