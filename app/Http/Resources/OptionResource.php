<?php

namespace App\Http\Resources;

use App\Enums\StateOption;
use App\Enums\TypeOption;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Option */
class OptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'type' => $this->type,
            'format_type' => TypeOption::getLabel($this->type),
            'state' => $this->state,
            'format_state' => StateOption::getLabel($this->state),
            'title' => $this->title,
            'price' => $this->price,
            'count' => $this->count,
        ];
    }
}
