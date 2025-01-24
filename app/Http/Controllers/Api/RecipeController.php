<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;

class RecipeController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Recipe(레시피)
     * @responseFile storage/responses/recipes.json
     */
    public function index()
    {
        return RecipeResource::collection(Recipe::all());
    }
 
    /** 상세
     * @group 사용자
     * @subgroup Recipe(레시피)
     * @responseFile storage/responses/recipe.json
     */
    public function show(Recipe $recipe)
    {
        return new RecipeResource($recipe);
    }


}
