<?php

namespace App\Http\Resources;

use App\Enums\DeliveryCompany;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Preset */
class PresetResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'price_delivery' => $this->price_delivery,
            'order_id' => $this->order_id,
            'cart_id' => $this->cart_id,
            'user_id' => $this->user_id,

            'price' => $this->price,
            'price_total' => $this->price_total,
            'price_discount' => $this->price_discount,
            'price_coupon' => $this->price_coupon,
            'coupon' => CouponResource::make($this->coupon),
            'count_option_required' => $this->count_option_required,
            'count_option_additional' => $this->count_option_additional,
            'presetProducts' => PresetProductMiniResource::collection($this->presetProducts),
            'order' => $this->order ? [
                'id' => $this->order->id,
                'merchant_uid' => $this->merchant_uid,
            ] : '',
        ];
    }
}
