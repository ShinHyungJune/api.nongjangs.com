<?php

namespace App\Http\Resources;

use App\Enums\DeliveryCompany;
use App\Enums\TypeOption;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PresetProductMiniResource extends JsonResource
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
            'price' => $this->price,
            'product' => $this->product_id ? [
                'id' => $this->product_id,
                'img' => $this->product ? $this->product->img : '',
                'title' => $this->product_title,
                'price' => $this->product_price,
                'price_origin' => $this->product_price_origin,
            ] : '',
            'package' => $this->package_id ? [
                'id' => $this->package_id,
                'count' => $this->package_count,
                'price' => $this->package_price,
                'type' => $this->package_type,
            ] : '',
            'option' => [
                'id' => $this->option_id,
                'title' => $this->option_title,
                'price' => $this->option_price,
                'type' => $this->option_type,
            ],
            'price_coupon' => $this->price_coupon,
            'count' => $this->count,
            'format_title' => $this->format_title,
            'format_created_at' => $this->preset->order ? Carbon::make($this->preset->order->created_at)->format('Y.m.d') : '',
        ];
    }
}
