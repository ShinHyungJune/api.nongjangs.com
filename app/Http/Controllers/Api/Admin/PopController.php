<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PopResource;
use App\Http\Requests\PopRequest;
use App\Models\Pop;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PopController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Pop(팝업)
     * @responseFile storage/responses/pops.json
     */
    public function index(PopRequest $request)
    {
        $items = new Pop();

        $items = $items->orderBy('order', 'asc')->paginate(25);

        return PopResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Pop(팝업)
     * @responseFile storage/responses/pop.json
     */
    public function show(Pop $pop)
    {
        return $this->respondSuccessfully(PopResource::make($pop));
    }

    /** 생성
     * @group 관리자
     * @subgroup Pop(팝업)
     * @responseFile storage/responses/pop.json
     */
    public function store(PopRequest $request)
    {
        $createdItem = Pop::create($request->validated());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(PopResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Pop(팝업)
     * @responseFile storage/responses/pop.json
     */
    public function update(PopRequest $request, Pop $pop)
    {
        $pop->update($request->validated());

        if($request->files_remove_ids){
            $medias = $pop->getMedia("img");

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
                $pop->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(PopResource::make($pop));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Pop(팝업)
     */
    public function destroy(PopRequest $request, Pop $pop)
    {
        $pop->delete();

        return $this->respondSuccessfully();
    }

    /** 앞순서로 변경
     * @group 관리자
     * @subgroup Pop(팝업)
     */
    public function up(Pop $pop, Request $request)
    {
        $prevOrder = $pop->order;

        $target = Pop::orderBy('order', 'desc')->where('id', '!=', $pop->id)->where('order', '<=', $pop->order)->first();

        if($target) {
            $changeOrder = $target->order == $pop->order ? $pop->order - 1 : $target->order;
            $pop->update(["order" => $changeOrder]);
            $target->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();
    }

    /** 뒷순서로 변경
     * @group 관리자
     * @subgroup Pop(팝업)
     */
    public function down(Pop $pop, Request $request)
    {
        $prevOrder = $pop->order;

        $target = Pop::orderBy("order", "asc")->where("id", "!=", $pop->id)->where("order", ">=", $pop->order)->first();

        if($target) {
            $changeOrder = $target->order == $pop->order ? $pop->order + 1 : $target->order;
            $pop->update(["order" => $changeOrder]);
            $target->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();
    }
}
