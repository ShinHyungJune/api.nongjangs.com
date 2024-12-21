<?php

namespace App\Http\Resources;

use App\Enums\TypeDelivery;
use App\Enums\TypeProduct;
use App\Models\AdditionalProduct;
use App\Models\Arr;
use App\Models\Category;
use App\Models\Like;
use App\Models\Logo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        // $category = Category::withTrashed()->find($this->category_id);


        return [
            'id' => $this->id,


            'ratio_discount' => $this->ratio_discount,
            'img' => $this->img ?? '', // 대표이미지
            'imgs' => $this->imgs, // 이미지 목록
            'imgs_prototype' => $this->imgs_prototype, // 시안
            'imgs_real' => $this->imgs_real, // 실제작 제품
            'imgs_circle' => $this->imgs_circle, // 360도 이미지
            'count_review' => $this->count_review,

            'colors' => ColorResource::collection($this->colors()->where('open', 1)->get()),
            'sizes' => SizeResource::collection($this->sizes()->where('open', 1)->get()),
            'products' => AdditionalProductResource::collection($this->products()->where('open', 1)->get()),

            'order' => $this->order,
            'category_ids' => $this->categories->pluck('id')->toArray(),
            'format_categories' => Arr::getArrayToString($this->categories->pluck('title')->toArray()),
            /*'category' => $category ? [
                'id' => $category->id,
                'title' => $category->title,
            ] : '',*/
            'open' => $this->open,
            'custom' => $this->custom,
            'count_view' => $this->count_view,
            'count_order' => $this->count_order,
            'real_count_order' => $this->real_count_order,
            'title' => $this->title,
            'description' => $this->description,
            'summary' => $this->summary,
            'price' => $this->price,
            'price_discount' => $this->price_discount,
            'price_origin' => $this->price_origin,
            'price_delivery' => $this->price_delivery,
            'pop' => $this->pop,
            'special' => $this->special,
            'recommend' => $this->recommend,
            'empty' => $this->empty,
            'duration' => $this->duration ?? '',
            'texture' => $this->texture ?? '',
            'type_delivery' => $this->type_delivery ?? '',
            'format_type_delivery' => TypeDelivery::getLabel($this->type_delivery),
            'creator' => $this->creator ?? '',
            'case' => $this->case ?? '',
            'way_to_create' => $this->way_to_create ?? '',
            'way_to_delivery' => $this->way_to_delivery ?? '',
            'active' => $this->active,

            'format_created_at' => Carbon::make($this->created_at)->format("m.d H:i"),

        ];
    }
}
