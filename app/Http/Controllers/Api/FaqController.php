<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends ApiController
{
    /**
     * @group Faqs(자주묻는질문)
     * @responseFile storage/responses/faqs.json
     */
    public function index(FaqRequest $request)
    {
        $items = new Faq();

        if($request->faq_category_id)
            $items = $items->where('faq_category_id', $request->faq_category_id);

        if($request->word)
            $items = $items->where('title', "LIKE", "%".$request->word."%");

        $items = $items->latest()->paginate(12);

        return FaqResource::collection($items);
    }
}
