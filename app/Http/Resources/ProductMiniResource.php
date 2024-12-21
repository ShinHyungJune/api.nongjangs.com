<?php

namespace App\Http\Resources;

use App\Enums\TypeProduct;
use App\Models\AdditionalProduct;
use App\Models\Category;
use App\Models\Like;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductMiniResource extends JsonResource
{
    public function toArray($request)
    {
        $category = Category::withTrashed()->find($this->category_id);

        return [
            'id' => $this->id,
            'ratio_discount' => $this->ratio_discount,
            'img' => $this->img ?? '', // 대표이미지
            'count_review' => $this->count_review,
            'category' => $category ? CategoryResource::make($category) : '',
            'count_view' => $this->count_view,
            'count_order' => $this->count_order,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'price_discount' => $this->price_discount,
            'price_origin' => $this->price_origin,
        ];
    }
}
