<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QnaCategoryRequest;
use App\Http\Resources\QnaCategoryResource;
use App\Models\QnaCategory;

class QnaCategoryController extends ApiController
{
    /** 목록
    * @group 사용자
    * @subgroup QnaCategory(문의카테고리)
     */
    public function index()
    {
        $items = QnaCategory::orderBy('id', 'asc')->paginate(30);

        return QnaCategoryResource::collection($items);
    }
}
