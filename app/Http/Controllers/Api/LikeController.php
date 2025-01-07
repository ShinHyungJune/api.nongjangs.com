<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LikeRequest;
use App\Http\Resources\LikeResource;
use App\Models\Like;

class LikeController extends ApiController
{
    /** 생성 또는 삭제 (토글)
     * @group 사용자
     * @subgroup Like(좋아요)
     */
    public function store(LikeRequest $request)
    {
        $like = auth()->user()->likes()
            ->where('likeable_id', $request->likeable_id)
            ->where('likeable_type', $request->likeable_type)
            ->first();

        $like
            ? $like->delete()
            : auth()->user()->likes()->create($request->all());

        return $this->respondSuccessfully();
    }

}
