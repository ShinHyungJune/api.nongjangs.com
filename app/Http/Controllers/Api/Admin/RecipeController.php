<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Http\Requests\RecipeRequest;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RecipeController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Recipe(레시피)
     * @responseFile storage/responses/recipes.json
     */
    public function index(RecipeRequest $request)
    {
        $items = Recipe::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(25);

        return RecipeResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Recipe(레시피)
     * @responseFile storage/responses/recipe.json
     */
    public function show(Recipe $recipe)
    {
        return $this->respondSuccessfully(RecipeResource::make($recipe));
    }

    /** 생성
     * @group 관리자
     * @subgroup Recipe(레시피)
     * @responseFile storage/responses/recipe.json
     */
    public function store(RecipeRequest $request)
    {
        $createdItem = Recipe::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(RecipeResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Recipe(레시피)
     * @responseFile storage/responses/recipe.json
     */
    public function update(RecipeRequest $request, Recipe $recipe)
    {
        $recipe->update($request->all());

        if($request->files_remove_ids){
            $medias = $recipe->getMedia("img");

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
                $recipe->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(RecipeResource::make($recipe));
    }

    /** 삭제피
     * @group 관리자
     * @subgroup Recipe(레시피)
     */
    public function destroy(RecipeRequest $request)
    {
        Recipe::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
