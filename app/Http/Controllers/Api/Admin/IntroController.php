<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\IntroResource;
use App\Http\Requests\IntroRequest;
use App\Models\Intro;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IntroController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup 모델명
     * @responseFile storage/responses/intros.json
     */
    public function index(IntroRequest $request)
    {
        $items = new Intro();

        $items = $items->latest()->paginate(10);

        return IntroResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup 모델명
     * @responseFile storage/responses/intro.json
     */
    public function show(Intro $intro)
    {
        return $this->respondSuccessfully(IntroResource::make($intro));
    }

    /** 생성
     * @group 관리자
     * @subgroup 모델명
     * @responseFile storage/responses/intro.json
     */
    public function store(IntroRequest $request)
    {
        $createdItem = Intro::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(IntroResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup 모델명
     * @responseFile storage/responses/intro.json
     */
    public function update(IntroRequest $request, Intro $intro)
    {
        $intro->update($request->all());

        if($request->files_remove_ids){
            $medias = $intro->getMedia("img");

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
                $intro->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(IntroResource::make($intro));
    }

    /** 삭제
     * @group 관리자
     * @subgroup 모델명
     */
    public function destroy(IntroRequest $request)
    {
        Intro::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
