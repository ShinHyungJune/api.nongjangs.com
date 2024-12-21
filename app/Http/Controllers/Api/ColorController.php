<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ColorResource;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        return ColorResource::collection(Color::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products'],
            'title' => ['required'],
            'open' => ['boolean'],
        ]);

        return new ColorResource(Color::create($data));
    }

    public function show(Color $color)
    {
        return new ColorResource($color);
    }

    public function update(Request $request, Color $color)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products'],
            'title' => ['required'],
            'open' => ['boolean'],
        ]);

        $color->update($data);

        return new ColorResource($color);
    }

    public function destroy(Color $color)
    {
        $color->delete();

        return response()->json();
    }
}
