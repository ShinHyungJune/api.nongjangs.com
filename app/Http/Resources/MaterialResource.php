<?php

namespace App\Http\Resources;

use App\Enums\TypeMaterial;
use App\Enums\TypePackageMaterial;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Material */
class MaterialResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'img' => $this->img ?? '',
            'category_id' => $this->category_id,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'title' => $this->category->title,
            ] : '',
            'type' => $this->type,
            'format_type' => TypeMaterial::getLabel($this->type),
            'title' => $this->title,
            'descriptions' => $this->descriptions,
            'format_descriptions' => $this->format_descriptions,
        ];
    }
}
