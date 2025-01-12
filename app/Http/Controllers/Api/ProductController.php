<?php

namespace App\Http\Controllers\Api;

use App\Enums\StateProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use League\Glide\Api\Api;

class ProductController extends ApiController
{
    public function index(ProductRequest $request)
    {
        $items = Product::withCount('reviews as count_review')
            ->where('state', StateProduct::ONGOING);

        $request['order_by'] = $request->order_by ?? 'count_review';

        if($request->word)
            $items = $items->where('title', 'LIKE', '%'.$request->word.'%');

        if($request->tag_ids)
            $items = $items->whereHas('tags', function ($query) use($request){
                $query->whereIn('tags.id', $request->tag_ids);
            });

        $items = $items->orderBy($request->order_by, 'desc')->paginate(12);

        return ProductResource::collection($items);
    }


    public function show(Product $product)
    {
        return $this->respondSuccessfully(ProductResource::make($product));
    }

}
