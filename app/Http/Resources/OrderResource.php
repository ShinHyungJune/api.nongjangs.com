<?php

namespace App\Http\Resources;

use App\Enums\StateOrder;
use App\Enums\TypeDelivery;
use App\Models\Arr;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'imp_uid' => $this->imp_uid,
            'merchant_uid' => $this->merchant_uid,

            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'user_email' => $this->user_email,
            'user_contact' => $this->user_contact,

            'buyer_name' => $this->buyer_name,
            'buyer_contact' => $this->buyer_contact,

            'delivery_name' => $this->delivery_name,
            'delivery_contact' => $this->delivery_contact,
            'delivery_address' => $this->delivery_address,
            'delivery_address_detail' => $this->delivery_address_detail,
            'delivery_address_zipcode' => $this->delivery_address_zipcode,
            'delivery_requirement' => $this->delivery_requirement,

            'price_products' => $this->price_products,
            'price_discount_products' => $this->price_discount_products,
            'price_origin_products' => $this->price_origin_products,
            'price_delivery' => $this->price_delivery,
            'price_coupon' => $this->price_coupon,
            'point_use' => $this->point_use,
            'price' => $this->price,

            'state' => $this->state,
            'memo' => $this->memo,
            'reason' => $this->reason,
            'process_success' => $this->process_success,

            'pay_method_id' => $this->pay_method_id,
            'pay_method_name' => $this->pay_method_name,
            'pay_method_pg' => $this->pay_method_pg,
            'pay_method_method' => $this->pay_method_method,
            'pay_method_channel_key' => $this->pay_method_channel_key,
            'pay_method_external' => $this->pay_method_external,
            'format_pay_method' => $this->format_pay_method,

            'vbank_num' => $this->vbank_num,
            'vbank_name' => $this->vbank_name,
            'vbank_date' => $this->vbank_date,

            'format_products' => $this->format_products,
            'format_state' => StateOrder::getLabel($this->state),
            'presets' => PresetResource::collection($this->presets),
            'presetProducts' => PresetProductResource::collection($this->presetProducts),
            'can_cancel' => $this->can_cancel,
            'admin_can_cancel' => $this->admin_can_cancel,
            'format_success_at' => $this->success_at ? Carbon::make($this->success_at)->format('Y.m.d H:i') : '',
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d H:i'),
        ];
    }
}
