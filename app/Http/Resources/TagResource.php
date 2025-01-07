<?php

namespace App\Http\Resources;

use App\Enums\TypeTag;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Tag */
class TagResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'format_type' => TypeTag::getLabel($this->type),
            'title' => $this->title,
            'color' => $this->color,
            'open' => $this->open,
            'order' => $this->order,
        ];
    }
}
