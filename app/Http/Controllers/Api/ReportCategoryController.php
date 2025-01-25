<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportCategoryRequest;
use App\Http\Resources\ReportCategoryResource;
use App\Models\ReportCategory;

class ReportCategoryController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup ReportCategory(신고유형)
     * @responseFile storage/responses/reportCategories.json
     */
    public function index()
    {
        return ReportCategoryResource::collection(ReportCategory::all());
    }
}
