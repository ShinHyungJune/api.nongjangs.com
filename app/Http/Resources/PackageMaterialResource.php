<?php

namespace App\Http\Resources;

use App\Enums\TypeMaterial;
use App\Enums\TypePackageMaterial;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageMaterialResource extends JsonResource
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
            'material' => MaterialResource::make($this->material),
            'img' => $this->img ?? '',
            'type' => $this->type,
            'format_type' => TypePackageMaterial::getLabel($this->type),
            'count' => $this->count,
            'value' => $this->value,
            'unit' => $this->unit,
            'price_origin' => $this->price_origin,
            'price' => $this->price,
            'tags' => TagResource::collection($this->tags),
        ];
    }
}
