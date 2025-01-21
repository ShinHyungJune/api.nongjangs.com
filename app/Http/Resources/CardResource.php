<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Card */
class CardResource extends JsonResource
{
    public function toArray($request)
    {
        $maskedNumber = preg_replace('/(\d{4})(\d{2})\d{2}(\d{4})(\d{4})/', '$1 $2** **** $4', $this->card_number);

        return [
            'id' => $this->id,
            'card_number' => $maskedNumber,
            'name' => $this->name,
            'color' => $this->color,
        ];
    }
}
