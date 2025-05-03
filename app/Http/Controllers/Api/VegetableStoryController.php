<?php

namespace App\Http\Controllers\Api;

use App\Enums\TypePointHistory;
use App\Http\Requests\VegetableStoryRequest;
use App\Http\Resources\VegetableStoryResource;
use App\Models\PointHistory;
use App\Models\PresetProduct;
use App\Models\VegetableStory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class VegetableStoryController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStories.json
     */
    public function index(VegetableStoryRequest $request)
    {
        $items = VegetableStory::withCount('likes as count_like');

        $request['order_by'] = $request->order_by ?? 'created_at';

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

        if($request->tag_ids)
            $items = $items->whereHas('tags', function ($query) use ($request){
                $query->whereIn('tags.id', $request->tag_ids);
            });

        if($request->word)
            $items = $items->where(function (Builder $query) use($request){
                $query->where('description', 'LIKE', '%'.$request->word."%")
                    ->orWhereHas('tags',function ($query) use ($request){
                        $query->where('tags.title', 'LIKE', '%'.$request->word.'%');
                    });
            });

        if($request->has_column)
            $items = $items->whereNotNull($request->has_column);

        if($request->package_id)
            $items = $items->where('package_id', $request->package_id);

        if($request->recipe_id)
            $items = $items->where('recipe_id', $request->recipe_id);

        $items = $items->orderBy($request->order_by, 'desc')->paginate(12);

        return VegetableStoryResource::collection($items);
    }

    /** 상세
     * @group 사용자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStory.json
     */
    public function show(VegetableStory $vegetableStory)
    {
        return $this->respondSuccessfully(VegetableStoryResource::make($vegetableStory));
    }

    /** 생성
     * @group 사용자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStory.json
     */
    public function store(VegetableStoryRequest $request)
    {
        $data = $request->validated();

        if($request->preset_product_id) {
            $presetProduct = PresetProduct::find($request->preset_product_id);

            if(!$presetProduct->can_vegetable_story)
                return $this->respondForbidden('권한이 없습니다.');
        }

        $vegetableStory = auth()->user()->vegetableStories()->create(Arr::except($data, ['imgs', 'tag_ids']));

        if(is_array($request->file("imgs"))){
            foreach($request->file("imgs") as $file){
                $vegetableStory->addMedia($file["file"])->preservingOriginal()->toMediaCollection("imgs", "s3");
            }
        }

        $vegetableStory->tags()->sync($request->tag_ids);

        $countPointHistories = auth()->user()->pointHistories()
            ->where('increase', 1)
            ->where('type', TypePointHistory::VEGETABLE_STORY_CREATED)
            ->count();

        if(isset(VegetableStory::$points[$countPointHistories])){
            $point = VegetableStory::$points[$countPointHistories];

            auth()->user()->givePoint($point, TypePointHistory::VEGETABLE_STORY_CREATED, $vegetableStory);
        }

        return $this->respondSuccessfully(VegetableStoryResource::make($vegetableStory));
    }

    /** 수정
     * @group 사용자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStory.json
     */
    public function update(VegetableStoryRequest $request, VegetableStory $vegetableStory)
    {
        if(auth()->id() != $vegetableStory->user_id)
            return $this->respondForbidden();

        $vegetableStory->update($request->validated());

        if($request->imgs_remove_ids){
            $medias = $vegetableStory->getMedia("imgs");

            foreach($medias as $media){
                foreach($request->imgs_remove_ids as $id){
                    if((int) $media->id == (int) $id){
                        $media->delete();
                    }
                }
            }
        }

        if(is_array($request->file("imgs"))){
            foreach($request->file("imgs") as $file){
                $vegetableStory->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        $vegetableStory->tags()->sync($request->tag_ids);

        return new VegetableStoryResource($vegetableStory);
    }

    /** 삭제
     * @group 사용자
     * @subgroup VegetableStory(채소이야기)
     */
    public function destroy(VegetableStory $vegetableStory)
    {
        if(auth()->id() != $vegetableStory->user_id)
            return $this->respondForbidden();

        $vegetableStory->delete();

        return $this->respondSuccessfully();
    }
}
