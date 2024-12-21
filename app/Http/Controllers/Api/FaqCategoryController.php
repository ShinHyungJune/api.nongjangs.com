<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqCategoryResource;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqCategoryController extends ApiController
{
    /**
     * @group FaqCategory(자주묻는질문 카테고리)
     * @responseFile storage/responses/faqCategories.json
     */
    public function index(Request $request)
    {
        $items = new FaqCategory();

        $items = $items->orderBy('id', 'asc')->paginate(30);

        return FaqCategoryResource::collection($items);
    }
}
