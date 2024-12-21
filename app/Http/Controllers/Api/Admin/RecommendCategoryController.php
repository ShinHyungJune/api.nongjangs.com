<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecommendCategoryRequest;
use App\Http\Resources\RecommendCategoryResource;
use App\Models\RecommendCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RecommendCategoryController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup RecommendCategory(추천 카테고리)
     * @priority 3
     * @responseFile storage/responses/recommendCategories.json
     */
    public function index(RecommendCategoryRequest $request)
    {
        $items = RecommendCategory::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(10);

        return RecommendCategoryResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup RecommendCategory(추천 카테고리)
     * @priority 3
     * @responseFile storage/responses/recommendCategory.json
     */
    public function show(RecommendCategory $recommendCategory)
    {
        return $this->respondSuccessfully(RecommendCategoryResource::make($recommendCategory));
    }

    /** 생성
     * @group 관리자
     * @subgroup RecommendCategory(추천 카테고리)
     * @priority 3
     * @responseFile storage/responses/recommendCategory.json
     */
    public function store(RecommendCategoryRequest $request)
    {
        $createdItem = RecommendCategory::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(RecommendCategoryResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup RecommendCategory(추천 카테고리)
     * @priority 3
     * @responseFile storage/responses/recommendCategory.json
     */
    public function update(RecommendCategoryRequest $request, RecommendCategory $recommendCategory)
    {
        $recommendCategory->update($request->all());

        if($request->files_remove_ids){
            $medias = $recommendCategory->getMedia("img");

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
                $recommendCategory->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(RecommendCategoryResource::make($recommendCategory));
    }

    /** 삭제
     * @group 관리자
     * @subgroup RecommendCategory(추천 카테고리)
     * @priority 3
     */
    public function destroy(RecommendCategoryRequest $request)
    {
        RecommendCategory::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
