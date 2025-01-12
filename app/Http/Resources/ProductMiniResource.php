<?php

namespace App\Http\Resources;

use App\Enums\DeliveryCompany;
use App\Enums\StateProduct;
use App\Enums\TypeDelivery;
use App\Enums\TypeDeliveryPrice;
use App\Enums\TypeOption;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductMiniResource extends JsonResource
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
            'img' => $this->img ?? '',
            'title' => $this->title,
            'price' => $this->price,
            'price_origin' => $this->price_origin,
        ];
    }
}
