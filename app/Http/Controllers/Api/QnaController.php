<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QnaRequest;
use App\Http\Resources\QnaResource;
use App\Models\Qna;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class QnaController extends ApiController
{
    /** 목록
     * @group  사용자
     * @subgroup  Qna(문의)
     * @responseFile storage/responses/qnas.json
     */
    public function index(Request $request)
    {
        $items = auth()->user()->qnas()->latest()->paginate(12);

        return QnaResource::collection($items);
    }

    /** 상세
     * @group  User
     * @subgroup  Qna(문의)
     * @responseFile storage/responses/qna.json
     */
    public function show(Qna $qna)
    {
        if($qna->user_id != auth()->id())
            return $this->respondForbidden();

        return $this->respondSuccessfully(QnaResource::make($qna));
    }

    /** 생성
     * @group  User
     * @subgroup  Qna(문의)
     * @responseFile storage/responses/qna.json
     */
    public function store(QnaRequest $request)
    {
        $data = $request->validated();

        $item = auth()->user()->qnas()->create($data);

        if(is_array($request->file("imgs"))){
            foreach($request->file("imgs") as $file){
                $item->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        return $this->respondSuccessfully(QnaResource::make($item));
    }

    /** 수정
     * @group  사용자
     * @subgroup  Qna(문의)
     */
    public function update(QnaRequest $request, Qna $qna)
    {
        if($qna->user_id != auth()->id())
            return $this->respondForbidden();

        if($qna->answer)
            return $this->respondForbidden('답변이 달린 문의는 수정할 수 없습니다.');

        if($request->imgs_remove_ids){
            $medias = $qna->getMedia("imgs");

            foreach($medias as $media){
                foreach($request->imgs_remove_ids as $id){
                    if((int) $media->id == (int) $id){
                        $media->delete();
                    }
                }
            }
        }

        if(is_array($request->file("imgs"))){
            foreach($request->file("imgs") as $file){
                $qna->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        $qna->update($request->validated());

        return $this->respondSuccessfully(QnaResource::make($qna));
    }
    
    /** 삭제
     * @group  사용자
     * @subgroup  Qna(문의)
     */
    public function destroy(Qna $qna)
    {
        if($qna->user_id != auth()->id())
            return $this->respondForbidden();

        $qna->delete();

        return $this->respondSuccessfully(QnaResource::make($qna));
    }

}
