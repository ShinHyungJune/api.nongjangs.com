<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExampleResource;
use App\Http\Requests\ExampleRequest;
use App\Models\Example;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExampleController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup 모델명(한글명)
     * @responseFile storage/responses/examples.json
     */
    public function index(ExampleRequest $request)
    {
        $items = Example::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(10);

        return ExampleResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup 모델명(한글명)
     * @responseFile storage/responses/example.json
     */
    public function show(Example $example)
    {
        return $this->respondSuccessfully(ExampleResource::make($example));
    }

    /** 생성
     * @group 관리자
     * @subgroup 모델명(한글명)
     * @responseFile storage/responses/example.json
     */
    public function store(ExampleRequest $request)
    {
        $createdItem = Example::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(ExampleResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup 모델명(한글명)
     * @responseFile storage/responses/example.json
     */
    public function update(ExampleRequest $request, Example $example)
    {
        $example->update($request->all());

        if($request->files_remove_ids){
            $medias = $example->getMedia("img");

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
                $example->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(ExampleResource::make($example));
    }

    /** 삭제
     * @group 관리자
     * @subgroup 모델명(한글명)
     */
    public function destroy(ExampleRequest $request)
    {
        Example::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
