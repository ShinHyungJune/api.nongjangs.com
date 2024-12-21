<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\PresetProduct;
use Illuminate\Http\Request;

class CommentController extends ApiController
{
    /** 목록
     * @group Comment(코멘트)
     * @responseFile storage/responses/comments.json
     */
    public function index(CommentRequest $request)
    {
        $presetProduct = PresetProduct::where('uuid', $request->preset_product_uuid)->first();

        if(!$presetProduct)
            return $this->respondForbidden('유효하지 않은 주문번호입니다.');

        $items = $presetProduct->comments()->latest()->paginate(60);

        return CommentResource::collection($items);
    }
}
