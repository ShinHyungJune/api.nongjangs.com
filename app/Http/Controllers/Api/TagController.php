<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Tag(태그)
     * @responseFile storage/responses/tags.json
     */
    public function index(TagRequest $request)
    {
        $items = Tag::where('open', 1);

        if($request->type)
            $items = $items->where('type', $request->type);

        if($request->word)
            $items = $items->where('title','LIKE','%'.$request->word.'%');

        $items = $items->orderBy('order', 'asc')->paginate(100);

        return TagResource::collection($items);
    }
}
