<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\FarmResource;
use App\Http\Requests\FarmRequest;
use App\Models\Farm;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FarmController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Farm(농가)
     * @responseFile storage/responses/farms.json
     */
    public function index(FarmRequest $request)
    {
        $items = Farm::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        if($request->county_id)
            $items = $items->where('county_id', $request->county_id);

        $items = $items->latest()->paginate($request->take ?? 50);

        return FarmResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Farm(농가)
     * @responseFile storage/responses/farms.json
     */
    public function show(Farm $farm)
    {
        return $this->respondSuccessfully(FarmResource::make($farm));
    }

    /** 생성
     * @group 관리자
     * @subgroup Farm(농가)
     * @responseFile storage/responses/farms.json
     */
    public function store(FarmRequest $request)
    {
        $createdItem = Farm::create($request->validated());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(FarmResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Farm(농가)
     * @responseFile storage/responses/farms.json
     */
    public function update(FarmRequest $request, Farm $farm)
    {
        $farm->update($request->validated());

        if($request->files_remove_ids){
            $medias = $farm->getMedia("img");

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
                $farm->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(FarmResource::make($farm));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Farm(농가)
     */
    public function destroy(FarmRequest $request)
    {
        Farm::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
