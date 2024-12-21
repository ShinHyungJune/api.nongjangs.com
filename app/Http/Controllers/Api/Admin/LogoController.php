<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\LogoResource;
use App\Http\Requests\LogoRequest;
use App\Models\Logo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogoController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup 모델명
     * @priority 1
     * @responseFile storage/responses/logos.json
     */
    public function index(LogoRequest $request)
    {
        $items = Logo::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(10);

        return LogoResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup 모델명
     * @priority 1
     * @responseFile storage/responses/logo.json
     */
    public function show(Logo $logo)
    {
        return $this->respondSuccessfully(LogoResource::make($logo));
    }

    /** 생성
     * @group 관리자
     * @subgroup 모델명
     * @priority 1
     * @responseFile storage/responses/logo.json
     */
    public function store(LogoRequest $request)
    {
        $createdItem = Logo::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(LogoResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup 모델명
     * @priority 1
     * @responseFile storage/responses/logo.json
     */
    public function update(LogoRequest $request, Logo $logo)
    {
        $logo->update($request->all());

        if($request->files_remove_ids){
            $medias = $logo->getMedia("img");

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
                $logo->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(LogoResource::make($logo));
    }

    /** 삭제
     * @group 관리자
     * @subgroup 모델명
     * @priority 1
     */
    public function destroy(LogoRequest $request)
    {
        Logo::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
