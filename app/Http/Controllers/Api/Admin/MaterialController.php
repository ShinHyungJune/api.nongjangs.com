<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\MaterialResource;
use App\Http\Requests\MaterialRequest;
use App\Models\Material;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MaterialController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Material(품목)
     * @responseFile storage/responses/materials.json
     */
    public function index(MaterialRequest $request)
    {
        $items = Material::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        if($request->type)
            $items = $items->where('type', $request->type);

        if($request->category_id)
            $items = $items->where('category_id', $request->category_id);

        $items = $items->latest()->paginate(25);

        return MaterialResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Material(품목)
     * @responseFile storage/responses/materials.json
     */
    public function show(Material $material)
    {
        return $this->respondSuccessfully(MaterialResource::make($material));
    }

    /** 생성
     * @group 관리자
     * @subgroup Material(품목)
     * @responseFile storage/responses/materials.json
     */
    public function store(MaterialRequest $request)
    {
        $request['descriptions'] = json_encode($request->descriptions);

        $createdItem = Material::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(MaterialResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Material(품목)
     * @responseFile storage/responses/materials.json
     */
    public function update(MaterialRequest $request, Material $material)
    {
        $request['descriptions'] = json_encode($request->descriptions);

        $material->update($request->all());

        if($request->files_remove_ids){
            $medias = $material->getMedia("img");

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
                $material->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(MaterialResource::make($material));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Material(품목)
     */
    public function destroy(MaterialRequest $request)
    {
        Material::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
