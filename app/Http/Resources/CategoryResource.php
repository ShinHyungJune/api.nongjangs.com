<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Category */
class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        $product = $this->products()->where('open', 1)->where('empty', 0)->inRandomOrder()->first();

        return [
            'id' => $this->id,
            'hide' => $this->hide,
            'order' => $this->order,
            'title' => $this->title,
            'imgs' => $this->imgs,
            'example' => $this->example ?? "",
            'product' => $product ? [
                'id' => $product->id,
                'img' => $product->img,
                'imgs' => $product->imgs,
                'summary' => $product->summary,
            ] : '',
        ];
    }
}
