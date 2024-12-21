<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecommendCategoryResource;
use App\Models\RecommendCategory;
use Illuminate\Http\Request;

class RecommendCategoryController extends Controller
{
    /**
     * @group RecommendCategory(추천카테고리)
     * @responseFile storage/responses/recommendCategories.json
     */
    public function index(Request $request)
    {
        $items = new RecommendCategory();

        $items = $items->latest()->paginate(30);

        return RecommendCategoryResource::collection($items);
    }

}
