<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Prototype */
class PrototypeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'creator' => $this->creator,
            'title' => $this->title,
            'confirmed' => $this->confirmed,
            'preset_product_id' => $this->preset_product_id,
            'presetProduct' => $this->presetProduct ? PresetProductResource::make($this->presetProduct) : '',
            'img' => $this->img ?? '',
            'imgs' => $this->imgs,
            'comments' => CommentResource::collection($this->comments),
        ];
    }
}
