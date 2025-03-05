<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\StateAnswer;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\QnaResource;
use App\Http\Requests\QnaRequest;
use App\Models\Qna;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QnaController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Qna(문의사항)
     * @responseFile storage/responses/qnas.json
     */
    public function index(QnaRequest $request)
    {
        $items = new Qna();

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

        if($request->state == StateAnswer::WAIT)
            $items = $items->where('answer', null);

        if($request->state == StateAnswer::FINISH)
            $items = $items->where('answer', '!=', null);

        if($request->qna_category_id)
            $items = $items->where('qna_category_id', '=', $request->qna_category_id);

        $items = $items->latest()->paginate(25);

        return QnaResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Qna(문의사항)
     * @responseFile storage/responses/qna.json
     */
    public function show(Qna $qna)
    {
        return $this->respondSuccessfully(QnaResource::make($qna));
    }

    /** 수정
     * @group 관리자
     * @subgroup Qna(문의사항)
     * @responseFile storage/responses/qna.json
     */
    public function update(QnaRequest $request, Qna $qna)
    {
        $qna->update([
            'answer' => $request->answer,
            'answered_at' => Carbon::now(),
            'answer_user_id' => auth()->id(),
        ]);

        if($request->files_remove_ids){
            $medias = $qna->getMedia("img");

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
                $qna->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(QnaResource::make($qna));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Qna(문의사항)
     */
    public function destroy(QnaRequest $request)
    {
        Qna::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
