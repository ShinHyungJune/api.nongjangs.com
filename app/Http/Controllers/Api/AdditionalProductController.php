<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdditionalProductResource;
use App\Models\AdditionalProduct;
use Illuminate\Http\Request;

class AdditionalProductController extends Controller
{
    public function index()
    {
        return AdditionalProductResource::collection(AdditionalProduct::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products'],
            'title' => ['required'],
            'price' => ['required', 'integer'],
            'open' => ['boolean'],
        ]);

        return new AdditionalProductResource(AdditionalProduct::create($data));
    }

    public function show(AdditionalProduct $additionalProduct)
    {
        return new AdditionalProductResource($additionalProduct);
    }

    public function update(Request $request, AdditionalProduct $additionalProduct)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products'],
            'title' => ['required'],
            'price' => ['required', 'integer'],
            'open' => ['boolean'],
        ]);

        $additionalProduct->update($data);

        return new AdditionalProductResource($additionalProduct);
    }

    public function destroy(AdditionalProduct $additionalProduct)
    {
        $additionalProduct->delete();

        return response()->json();
    }
}
