<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqCategoryRequest;
use App\Http\Resources\FaqCategoryResource;
use App\Models\FaqCategory;

class FaqCategoryController extends ApiController
{

    /** 목록
     * @group 사용자
     * @subgroup FaqCategories(자주묻는질문 유형)
     * @responseFile storage/responses/faqCategories.json
     */
    public function index()
    {
        $items = new FaqCategory();

        $items = $items->latest()->paginate(30);

        return FaqCategoryResource::collection($items);
    }
}
