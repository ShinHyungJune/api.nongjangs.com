<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeedbackResource;
use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeedbackController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Feedback(피드백)
     * @priority 16
     * @responseFile storage/responses/feedbacks.json
     */
    public function index(FeedbackRequest $request)
    {
        $items = Feedback::where(function($query) use($request){
            $query->where("description", "LIKE", "%".$request->word."%");
        });

        if($request->preset_product_id)
            $items = $items->where('preset_product_id', $request->preset_product_id);

        $items = $items->latest()->paginate(10);

        return FeedbackResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Feedback(피드백)
     * @priority 16
     * @responseFile storage/responses/feedback.json
     */
    public function show(Feedback $feedback)
    {
        return $this->respondSuccessfully(FeedbackResource::make($feedback));
    }

    /** 생성
     * @group 관리자
     * @subgroup Feedback(피드백)
     * @priority 16
     * @responseFile storage/responses/feedback.json
     */
    public function store(FeedbackRequest $request)
    {
        $createdItem = Feedback::create(array_merge($request->all(), ['admin' => true, 'check' => true]));

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(FeedbackResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Feedback(피드백)
     * @priority 16
     * @responseFile storage/responses/feedback.json
     */
    public function update(FeedbackRequest $request, Feedback $feedback)
    {
        $feedback->update($request->all());

        if($request->files_remove_ids){
            $medias = $feedback->getMedia("img");

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
                $feedback->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(FeedbackResource::make($feedback));
    }

    public function check(Feedback $feedback)
    {
        $feedback->update(['check' => true]);

        return $this->respondSuccessfully(FeedbackResource::make($feedback));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Feedback(피드백)
     * @priority 16
     */
    public function destroy(FeedbackRequest $request)
    {
        Feedback::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
