<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Category(카테고리)
     * @responseFile storage/responses/categories.json
     */
    public function index(CategoryRequest $request)
    {
        $items = Category::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        if($request->type)
            $items = $items->where('type', $request->type);

        if($request->category_id)
            $items = $items->where('category_id', $request->category_id);
        else
            $items = $items->whereNull('category_id');

        $items = $items->orderBy('order', 'asc')->paginate(25);

        return CategoryResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Category(카테고리)
     * @responseFile storage/responses/category.json
     */
    public function show(Category $category)
    {
        return $this->respondSuccessfully(CategoryResource::make($category));
    }

    /** 생성
     * @group 관리자
     * @subgroup Category(카테고리)
     * @responseFile storage/responses/category.json
     */
    public function store(CategoryRequest $request)
    {
        $createdItem = Category::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("example", "s3");
            }
        }

        return $this->respondSuccessfully(CategoryResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Category(카테고리)
     * @responseFile storage/responses/category.json
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        if($request->files_remove_ids){
            $medias = $category->getMedia("example");

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
                $category->addMedia($file["file"])->toMediaCollection("example", "s3");
            }
        }

        return $this->respondSuccessfully(CategoryResource::make($category));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Category(카테고리)
     */
    public function destroy(CategoryRequest $request)
    {
        Category::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }

    public function up(Category $category)
    {
        $prevOrder = $category->order;

        $target = Category::orderBy('order', 'desc')->where('id', '!=', $category->id)->where('order', '<=', $category->order)->first();

        if($target) {
            $changeOrder = $target->order == $category->order ? $category->order - 1 : $target->order;
            $category->update(["order" => $changeOrder]);
            $target->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();
    }

    public function down(Category $category)
    {
        $prevOrder = $category->order;

        $target = Category::orderBy("order", "asc")->where("id", "!=", $category->id)->where("order", ">=", $category->order)->first();

        if($target) {
            $changeOrder = $target->order == $category->order ? $category->order + 1 : $target->order;
            $category->update(["order" => $changeOrder]);
            $target->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();
    }
}
