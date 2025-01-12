<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagController extends ApiController
{
    public function index(TagRequest $request)
    {
        $items = Tag::where('open', 1);

        if($request->type)
            $items = $items->where('type', $request->type);

        $items = $items->orderBy('order', 'asc')->paginate(100);

        return TagResource::collection($items);
    }
}
