<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\LikeRequest;
use App\Http\Resources\LikeResource;
use App\Models\Like;

class LikeController extends ApiController
{

    public function store(LikeRequest $request)
    {
        return new LikeResource(Like::create($request->validated()));
    }

}
