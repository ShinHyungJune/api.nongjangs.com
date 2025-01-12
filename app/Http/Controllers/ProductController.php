<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'state' => ['required', 'integer'],
            'category_id' => ['required', 'exists:categories'],
            'farm_id' => ['required', 'exists:farms'],
            'city_id' => ['required', 'exists:cities'],
            'county_id' => ['required', 'exists:counties'],
            'uuid' => ['required'],
            'title' => ['required'],
            'price' => ['required', 'integer'],
            'price_origin' => ['required', 'integer'],
            'need_tax' => ['boolean'],
            'can_use_coupon' => ['boolean'],
            'can_use_point' => ['boolean'],
            'count' => ['required', 'integer'],
            'type_delivery' => ['required', 'integer'],
            'delivery_company' => ['required', 'integer'],
            'type_delivery_price' => ['required', 'integer'],
            'price_delivery' => ['required', 'integer'],
            'prices_delivery' => ['nullable'],
            'min_price_for_free_delivery_price' => ['required', 'integer'],
            'can_delivery_far_place' => ['boolean'],
            'delivery_price_far_place' => ['required', 'integer'],
            'delivery_company_refund' => ['required', 'integer'],
            'delivery_price_refund' => ['required', 'integer'],
            'delivery_address_refund' => ['nullable'],
            'description' => ['required'],
        ]);

        return new ProductResource(Product::create($data));
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'state' => ['required', 'integer'],
            'category_id' => ['required', 'exists:categories'],
            'farm_id' => ['required', 'exists:farms'],
            'city_id' => ['required', 'exists:cities'],
            'county_id' => ['required', 'exists:counties'],
            'uuid' => ['required'],
            'title' => ['required'],
            'price' => ['required', 'integer'],
            'price_origin' => ['required', 'integer'],
            'need_tax' => ['boolean'],
            'can_use_coupon' => ['boolean'],
            'can_use_point' => ['boolean'],
            'count' => ['required', 'integer'],
            'type_delivery' => ['required', 'integer'],
            'delivery_company' => ['required', 'integer'],
            'type_delivery_price' => ['required', 'integer'],
            'price_delivery' => ['required', 'integer'],
            'prices_delivery' => ['nullable'],
            'min_price_for_free_delivery_price' => ['required', 'integer'],
            'can_delivery_far_place' => ['boolean'],
            'delivery_price_far_place' => ['required', 'integer'],
            'delivery_company_refund' => ['required', 'integer'],
            'delivery_price_refund' => ['required', 'integer'],
            'delivery_address_refund' => ['nullable'],
            'description' => ['required'],
        ]);

        $product->update($data);

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json();
    }
}
