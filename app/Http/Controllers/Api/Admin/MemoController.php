<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\MemoRequest;
use App\Http\Resources\MemoResource;
use App\Models\Memo;

class MemoController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Memo(메모)
     * @responseFile storage/responses/memos.json
     */
    public function index(MemoRequest $request)
    {
        $items = new Memo();

        if($request->memoable_id)
            $items = $items->where('memoable_id', $request->memoable_id);

        if($request->memoable_type)
            $items = $items->where('memoable_type', $request->memoable_type);

        $items = $items->latest()->paginate(12);

        return MemoResource::collection($items);
    }

    /** 생성
     * @group 관리자
     * @subgroup Memo(메모)
     * @responseFile storage/responses/memos.json
     */
    public function store(MemoRequest $request)
    {
        $memo = Memo::create(array_merge($request->all(), [
            'user_id' => auth()->id()
        ]));

        return $this->respondSuccessfully(MemoResource::make($memo));
    }


}
