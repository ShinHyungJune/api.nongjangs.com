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

            'need_tax' => $this->need_tax,
            'tax_business_number' => $this->tax_business_number,
            'tax_company_title' => $this->tax_company_title,
            'tax_company_president' => $this->tax_company_president,
            'tax_company_type' => $this->tax_company_type,
            'tax_company_category' => $this->tax_company_category,
            'tax_email' => $this->tax_email,
            'tax_name' => $this->tax_name,
            'tax_contact' => $this->tax_contact,
            'tax_address' => $this->tax_address,

            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'user_email' => $this->user_email,
            'user_contact' => $this->user_contact,

            'buyer_name' => $this->buyer_name,
            'buyer_email' => $this->buyer_email,
            'buyer_contact' => $this->buyer_contact,
            'buyer_address' => $this->buyer_address,
            'buyer_address_detail' => $this->buyer_address_detail,
            'buyer_address_zipcode' => $this->buyer_address_zipcode,

            'delivery_name' => $this->delivery_name,
            'delivery_contact' => $this->delivery_contact,
            'delivery_address' => $this->delivery_address,
            'delivery_address_detail' => $this->delivery_address_detail,
            'delivery_address_zipcode' => $this->delivery_address_zipcode,
            'delivery_requirement' => $this->delivery_requirement,
            'type_delivery' => $this->type_delivery,

            'point_use' => $this->point_use,
            'price_products' => $this->price_products,
            'price_products_discount' => $this->price_products_discount,
            'price_delivery' => $this->price_delivery,
            'price_coupon_discount' => $this->price_coupon_discount,

            'coupon' => $this->coupon ? CouponResource::make($this->coupon) : '',
            'agree_open' => $this->agree_open,

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

            'format_state' => StateOrder::getLabel($this->state),
            'presets' => PresetResource::collection($this->presets),
            'presentPresetProduct' => PresetProductResource::make($this->presentPresetProduct),
            'can_cancel' => $this->can_cancel,
            'admin_can_cancel' => $this->admin_can_cancel,
            'check_receipt' => $this->check_receipt,
            'format_type_delivery' => TypeDelivery::getLabel($this->type_delivery),
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d H:i'),
        ];
    }
}
