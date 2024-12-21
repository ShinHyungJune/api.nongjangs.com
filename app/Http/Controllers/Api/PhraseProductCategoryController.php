<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PhraseProductCategoryResource;
use App\Models\PhraseProductCategory;
use Illuminate\Http\Request;

class PhraseProductCategoryController extends ApiController
{
    /**
     * @group PhraseProductCategory (문구상품 카테고리)
     * @responseFile storage/responses/phraseProductCategories.json
     */
    public function index()
    {
        $items = new PhraseProductCategory();

        $items = $items->oldest()->paginate(300);

        return PhraseProductCategoryResource::collection($items);
    }

}
