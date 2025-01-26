<?php

namespace App\Http\Resources;

use App\Enums\DeliveryCompany;
use App\Enums\TypeOption;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PresetProduct */
class PresetProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'state' => $this->state,
            'price' => $this->price,
            'product' => $this->product_id ? [
                'id' => $this->product_id,
                'img' => $this->product ? $this->product->img : '',
                'title' => $this->product_title,
                'price' => $this->product_price,
                'price_origin' => $this->product_price_origin,
                'requiredOptions' => $this->product ? OptionResource::collection($this->product->options()->where('type', TypeOption::REQUIRED)->get()) : [],
                'additionalOptions' => $this->product ? OptionResource::collection($this->product->options()->where('type', TypeOption::ADDITIONAL)->get()) : [],
                'tags' => $this->product ? TagResource::collection($this->product->tags) : [],
            ] : '',
            'products_price' => $this->products_price,
            'package' => $this->package_id ? [
                'id' => $this->package_id,
                'count' => $this->package_count,
                'price' => $this->package_price,
                'type' => $this->package_type,
                'tags' => $this->package ? TagResource::collection($this->package->tags) : [],
            ] : '',
            'option' => [
                'id' => $this->option_id,
                'title' => $this->option_title,
                'price' => $this->option_price,
                'type' => $this->option_type,
            ],
            /*'product_title' => $this->product_title,
            'product_price' => $this->product_price,
            'product_price_origin' => $this->product_price_origin,*/
            'price_coupon' => $this->price_coupon,
            'count' => $this->count,
            /*'option_title' => $this->option_title,
            'option_price' => $this->option_price,
            'option_type' => $this->option_type,*/

            'preset_id' => $this->preset_id,
            'product_id' => $this->product_id,
            'option_id' => $this->option_id,
            'coupon_id' => $this->coupon_id,

            'delivery_name' => $this->delivery_name,
            'delivery_contact' => $this->delivery_contact,
            'delivery_address' => $this->delivery_address,
            'delivery_address_detail' => $this->delivery_address_detail,
            'delivery_address_zipcode' => $this->delivery_address_zipcode,
            'delivery_requirement' => $this->delivery_requirement,
            'delivery_number' => $this->delivery_number,
            'delivery_company' => $this->delivery_company,
            'format_delivery_company' => $this->delivery_company ? DeliveryCompany::getLabel($this->delivery_company) : '',
            'delivery_at' => $this->delivery_at ? Carbon::make($this->delivery_at)->format('Y.m.d') : '',
            'format_title' => $this->format_title,
            'format_created_at' => $this->preset->order ? Carbon::make($this->preset->order->created_at)->format('Y.m.d') : '',

            /*'preset' => new PresetResource($this->whenLoaded('preset')),
            'product' => new ProductResource($this->whenLoaded('product')),
            'option' => new OptionResource($this->whenLoaded('option')),
            'coupon' => new CouponResource($this->whenLoaded('coupon')),*/
        ];
    }
}
