<?php

namespace App\Http\Resources;

use App\Enums\TypeCouponHistory;
use App\Enums\TypePointHistory;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CouponHistory */
class CouponHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        $order = $this->order;
        $coupon = $this->order->coupon;

        return [
            'id' => $this->id,

            'type' => $this->type,
            'increase' => $this->increase,

            'coupon' => $coupon ? CouponResource::make($coupon) : '',

            'memo' => $this->memo,

            'format_increase' => $this->format_increase,
            'order' => $order ? [
                'id' => $order->id,
                'merchant_uid' => $order->merchant_uid,
                'format_created_at' => Carbon::make($order->created_at)->format('Y.m.d H:i'),
            ] : '',
            'format_type' => TypeCouponHistory::getLabel($this->type),
            'format_created_at' => $this->created_at ? Carbon::make($this->created_at)->format('Y-m-d H:i') : '',
        ];
    }
}
