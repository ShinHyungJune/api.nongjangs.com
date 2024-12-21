<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\EstimateResource;
use App\Http\Requests\EstimateRequest;
use App\Models\Estimate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EstimateController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Estimate(견적요청)
     * @priority 10
     * @responseFile storage/responses/estimates.json
     */
    public function index(EstimateRequest $request)
    {
        $items = Estimate::where(function($query) use($request){
            $query->where("email", "LIKE", "%".$request->word."%")
                ->orWhere("name", "LIKE", "%".$request->word."%")
                ->orWhere("contact", "LIKE", "%".$request->word."%")
                ->orWhere("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(10);

        return EstimateResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Estimate(견적요청)
     * @priority 10
     * @responseFile storage/responses/estimate.json
     */
    public function show(Estimate $estimate)
    {
        return $this->respondSuccessfully(EstimateResource::make($estimate));
    }

    /** 생성
     * @group 관리자
     * @subgroup Estimate(견적요청)
     * @priority 10
     * @responseFile storage/responses/estimate.json
     */
    public function store(EstimateRequest $request)
    {
        $createdItem = Estimate::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(EstimateResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Estimate(견적요청)
     * @priority 10
     * @responseFile storage/responses/estimate.json
     */
    public function update(EstimateRequest $request, Estimate $estimate)
    {
        $estimate->update($request->all());

        if($request->files_remove_ids){
            $medias = $estimate->getMedia("img");

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
                $estimate->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(EstimateResource::make($estimate));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Estimate(견적요청)
     * @priority 10
     */
    public function destroy(EstimateRequest $request)
    {
        Estimate::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
