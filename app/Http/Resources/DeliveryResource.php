<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Delivery */
class DeliveryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'main' => $this->main,
            'name' => $this->name,
            'contact' => $this->contact,
            'address' => $this->address,
            'address_detail' => $this->address_detail,
            'address_zipcode' => $this->address_zipcode,
        ];
    }
}
