<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Http\Requests\ReportRequest;
use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Report(신고)
     * @responseFile storage/responses/reports.json
     */
    public function index(ReportRequest $request)
    {
        $items = new Report();

        if($request->user_id)
            $items = $items->whereHas('reportable', function ($query) use($request){
                $query->where('user_id', $request->user_id);
            });

        $items = $items->latest()->paginate(10);

        return ReportResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Report(신고)
     * @responseFile storage/responses/report.json
     */
    public function show(Report $report)
    {
        return $this->respondSuccessfully(ReportResource::make($report));
    }

    /** 생성
     * @group 관리자
     * @subgroup Report(신고)
     * @responseFile storage/responses/report.json
     */
    public function store(ReportRequest $request)
    {
        $createdItem = Report::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(ReportResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Report(신고)
     * @responseFile storage/responses/report.json
     */
    public function update(ReportRequest $request, Report $report)
    {
        $report->update($request->all());

        if($request->files_remove_ids){
            $medias = $report->getMedia("img");

            foreach($medias as $media){
                foreach($request->files_remove_ids as $id){
                    if((int) $media->id == (int) $id){
                        $media->delete();
                    }
                }
            }
        }

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $report->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(ReportResource::make($report));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Report(신고)
     */
    public function destroy(ReportRequest $request)
    {
        Report::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
