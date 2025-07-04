<?php

namespace App\Http\Resources;

use App\Enums\DeliveryCompany;
use App\Enums\StateProduct;
use App\Enums\TypeDelivery;
use App\Enums\TypeDeliveryPrice;
use App\Enums\TypeOption;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [

            'id' => $this->id,

            'img' => $this->img ?? '',
            'imgs' => $this->imgs,
            'count_option' => $this->options()->count(),
            'requiredOptions' => OptionResource::collection($this->options()->where('type', TypeOption::REQUIRED)->get()),
            'additionalOptions' => OptionResource::collection($this->options()->where('type', TypeOption::ADDITIONAL)->get()),
            'ratio_discount' => $this->ratio_discount,
            'average_review' => round($this->average_review, 1),
            'count_review' => $this->count_review,
            'tags' => TagResource::collection($this->tags),

            'state' => $this->state,
            'format_state' => StateProduct::getLabel($this->state),
            'uuid' => $this->uuid,
            'title' => $this->title,
            'price' => $this->price,
            'price_origin' => $this->price_origin,
            'need_tax' => $this->need_tax,
            'can_use_coupon' => $this->can_use_coupon,
            'can_use_point' => $this->can_use_point,
            'count' => $this->count,
            'count_order' => $this->count_order,
            'type_delivery' => $this->type_delivery,
            'format_type_delivery' => TypeDelivery::getLabel($this->type_delivery),
            'deliveryCompany' => $this->deliveryCompany,
            'deliveryCompanyRefund' => $this->deliveryCompanyRefund,
            'type_delivery_price' => $this->type_delivery_price,
            'format_type_delivery_price' => TypeDeliveryPrice::getLabel($this->type_delivery_price),
            'price_delivery' => $this->price_delivery,
            'prices_delivery' => $this->prices_delivery ? json_decode($this->prices_delivery) : [],
            'min_price_for_free_delivery_price' => $this->min_price_for_free_delivery_price,
            'can_delivery_far_place' => $this->can_delivery_far_place,
            'ranges_far_place' => $this->ranges_far_place ? json_decode($this->ranges_far_place) : [],
            'delivery_price_refund' => $this->delivery_price_refund,
            'delivery_address_refund' => $this->delivery_address_refund,
            'description' => $this->description,

            'category_id' => $this->category_id,
            'farm_id' => $this->farm_id,
            'city_id' => $this->city_id,
            'county_id' => $this->county_id,

            'category' => $this->category ? CategoryResource::make($this->category) : '',
            'farm' => $this->farm ? FarmResource::make($this->farm) : '',
            'county' => $this->county ? CountyResource::make($this->county) : '',
            'empty' => $this->empty,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
