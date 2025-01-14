<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;

class ReportController extends ApiController
{
    /** 생성
     * @group 사용자
     * @subgroup 신고(Report)
     */
    public function store(ReportRequest $request)
    {
        $report = auth()->user()->reports()->where('reportable_type', $request->reportable_type)
            ->where('reportable_id', $request->reportable_id)
            ->first();

        if($report)
            return $this->respondForbidden('이미 신고가 접수되었습니다.');

        $report = auth()->user()->reports()->create($request->all());

        return $this->respondSuccessfully();
    }
}
