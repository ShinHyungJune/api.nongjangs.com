<?php

namespace App\Http\Resources;

use App\Enums\TypeCategory;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Category */
class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'category_id' => $this->category_id,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'title' => $this->category->title
            ] : '',
            'format_type' => TypeCategory::getLabel($this->type),
            'order' => $this->order,
            'title' => $this->title,
            'materials' => MaterialResource::collection($this->materials),
        ];
    }
}
