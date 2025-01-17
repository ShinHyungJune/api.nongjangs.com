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

            'point_use' => $this->point_use,
            /*'price_products' => $this->price_products,
            'price_products_discount' => $this->price_products_discount,
            'price_delivery' => $this->price_delivery,
            'price_coupon_discount' => $this->price_coupon_discount,*/

            'price' => $this->price,
            'state' => $this->state,
            'memo' => $this->memo,
            'reason' => $this->reason,
            'process_success' => $this->process_success,

            'pay_method_id' => $this->pay_method_id,
            'pay_method_name' => $this->pay_method_name,
            'pay_method_pg' => $this->pay_method_pg,
            'pay_method_method' => $this->pay_method_method,

            'vbank_num' => $this->vbank_num,
            'vbank_name' => $this->vbank_name,
            'vbank_date' => $this->vbank_date,

            'format_products' => $this->format_products,
            'format_state' => StateOrder::getLabel($this->state),
            'presets' => PresetResource::collection($this->presets),
            'can_cancel' => $this->can_cancel,
            'admin_can_cancel' => $this->admin_can_cancel,
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d H:i'),
        ];
    }
}
