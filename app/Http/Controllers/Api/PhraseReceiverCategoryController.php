<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PhraseProductCategoryResource;
use App\Http\Resources\PhraseReceiverCategoryResource;
use App\Models\PhraseProductCategory;
use App\Models\PhraseReceiverCategory;
use Illuminate\Http\Request;

class PhraseReceiverCategoryController extends ApiController
{
    /**
     * @group PhraseReceiverCategory (문구수신자 카테고리)
     * @responseFile storage/responses/phraseReceiverCategories.json
     */
    public function index()
    {
        $items = new PhraseReceiverCategory();

        $items = $items->oldest()->paginate(300);

        return PhraseReceiverCategoryResource::collection($items);
    }
}
