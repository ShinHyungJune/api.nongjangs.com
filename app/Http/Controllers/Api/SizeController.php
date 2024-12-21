<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SizeResource;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        return SizeResource::collection(Size::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products'],
            'title' => ['required'],
            'price' => ['required', 'integer'],
            'open' => ['boolean'],
        ]);

        return new SizeResource(Size::create($data));
    }

    public function show(Size $size)
    {
        return new SizeResource($size);
    }

    public function update(Request $request, Size $size)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products'],
            'title' => ['required'],
            'price' => ['required', 'integer'],
            'open' => ['boolean'],
        ]);

        $size->update($data);

        return new SizeResource($size);
    }

    public function destroy(Size $size)
    {
        $size->delete();

        return response()->json();
    }
}
