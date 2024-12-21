<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecommendResource;
use App\Models\Recommend;
use Illuminate\Http\Request;

class RecommendController extends Controller
{
    public function index()
    {
        return RecommendResource::collection(Recommend::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories'],
            'description' => ['required'],
        ]);

        return new RecommendResource(Recommend::create($data));
    }

    public function show(Recommend $recommend)
    {
        return new RecommendResource($recommend);
    }

    public function update(Request $request, Recommend $recommend)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories'],
            'description' => ['required'],
        ]);

        $recommend->update($data);

        return new RecommendResource($recommend);
    }

    public function destroy(Recommend $recommend)
    {
        $recommend->delete();

        return response()->json();
    }
}
