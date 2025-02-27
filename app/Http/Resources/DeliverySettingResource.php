<?php

namespace App\Http\Resources;

use App\Enums\DeliveryCompany;
use App\Enums\TypeDelivery;
use App\Enums\TypeDeliveryPrice;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\DeliverySetting */
class DeliverySettingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type_delivery' => $this->type_delivery,
            'format_type_delivery' => TypeDelivery::getLabel($this->type_delivery),
            'delivery_company' => $this->delivery_company,
            'format_delivery_company' => DeliveryCompany::getLabel($this->delivery_company),
            'type_delivery_price' => $this->type_delivery_price,
            'format_type_delivery_price' => TypeDeliveryPrice::getLabel($this->type_delivery_price),
            'price_delivery' => $this->price_delivery,
            'prices_delivery' => $this->prices_delivery ? json_decode($this->prices_delivery) : [],
            'ranges_far_place' => $this->ranges_far_place ? json_decode($this->ranges_far_place) : [],
            'min_price_for_free_delivery_price' => $this->min_price_for_free_delivery_price,
            'can_delivery_far_place' => $this->can_delivery_far_place,
            'delivery_price_far_place' => $this->delivery_price_far_place,
        ];
    }
}
