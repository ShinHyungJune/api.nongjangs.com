<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Builder;

class RecipeController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Recipe(레시피)
     * @responseFile storage/responses/recipes.json
     */
    public function index(RecipeRequest $request)
    {
        $items = Recipe::withCount('likes as count_like');

        $request['order_by'] = $request->order_by ?? 'count_like';

        if($request->word)
            $items = $items->where(function (Builder $query) use($request){
                $query->where('title', 'LIKE', '%'.$request->word."%")
                    ->orWhereHas('tags',function ($query) use ($request){
                        $query->where('tags.title', 'LIKE', '%'.$request->word.'%');
                    });
            });

        if($request->tag_ids)
            $items = $items->whereHas('tags', function ($query) use($request){
                $query->whereIn('tags.id', $request->tag_ids);
            });

        if($request->package_id)
            $items = $items->whereHas('packages', function ($query) use($request){
                $query->where('packages.id', $request->package_id);
            });

        if($request->except_package_id)
            $items = $items->whereDoesntHave('packages', function ($query) use($request){
                $query->where('packages.id', $request->except_package_id);
            });

        if(isset($request->is_bookmark)){
            $items = $request->is_bookmark ? $items->whereHas('bookmarks', function ($query){
                $query->where('bookmarks.user_id', auth()->id());
            }) : $items->whereDoesntHave('bookmarks', function ($query){
                $query->where('bookmarks.user_id', auth()->id());
            });
        }

        $items = $items->orderBy($request->order_by, 'desc')->paginate(12);

        return RecipeResource::collection($items);
    }
 
    /** 상세
     * @group 사용자
     * @subgroup Recipe(레시피)
     * @responseFile storage/responses/recipe.json
     */
    public function show(Recipe $recipe)
    {
        $recipe->update(['count_view' => $recipe->count_view + 1]);

        return $this->respondSuccessfully(RecipeResource::make($recipe));
    }

}
