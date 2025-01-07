<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\BookmarkRequest;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;

class BookmarkController extends ApiController
{
    public function store(BookmarkRequest $request)
    {
        return new BookmarkResource(Bookmark::create($request->validated()));
    }
}
