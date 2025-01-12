<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresetProductRequest;
use App\Http\Resources\PresetProductResource;
use App\Models\PresetProduct;

class PresetProductController extends Controller
{
    public function index()
    {
        return PresetProductResource::collection(PresetProduct::all());
    }

    public function store(PresetProductRequest $request)
    {
        return new PresetProductResource(PresetProduct::create($request->validated()));
    }

    public function show(PresetProduct $presetProduct)
    {
        return new PresetProductResource($presetProduct);
    }

    public function update(PresetProductRequest $request, PresetProduct $presetProduct)
    {
        $presetProduct->update($request->validated());

        return new PresetProductResource($presetProduct);
    }

    public function destroy(PresetProduct $presetProduct)
    {
        $presetProduct->delete();

        return response()->json();
    }
}
