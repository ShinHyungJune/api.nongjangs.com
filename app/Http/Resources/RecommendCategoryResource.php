<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\RecommendCategory */
class RecommendCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        $category = Category::withTrashed()->find($this->category_id);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,

            'category_id' => $this->category_id,

            'category' => $category ? [
                'id' => $category->id,
                'title' => $category->title,
            ] : '',

            'img' => $this->img ?? '',
        ];
    }
}
