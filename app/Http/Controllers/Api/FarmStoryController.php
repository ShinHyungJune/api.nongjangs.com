<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\FarmStoryRequest;
use App\Http\Resources\FarmStoryResource;
use App\Models\FarmStory;

class FarmStoryController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup FarmStory(농가이야기)
     * @responseFile storage/responses/farmStories.json
     */
    public function index(FarmStoryRequest $request)
    {
        $items = FarmStory::withCount([
            'likes',
            'likes as count_like'
        ]);

        $request['order_by'] = $request->order_by ?? 'count_like';

        if($request->word)
            $items = $items->where('title', 'LIKE', '%'.$request->word.'%');

        if($request->tag_ids)
            $items = $items->whereHas('tags', function ($query) use ($request){
                $query->whereIn('tags.id', $request->tag_ids);
            });

        if($request->farm_id)
            $items = $items->where('farm_id', $request->farm_id);

        if($request->exclude_farm_id)
            $items = $items->where('farm_id', '!=', $request->exclude_farm_id);

        $items = $items->orderBy($request->order_by, 'desc')->paginate(12);

        return FarmStoryResource::collection($items);
    }

    /** 상세
     * @group 사용자
     * @subgroup FarmStory(농가이야기)
     * @responseFile storage/responses/farmStory.json
     */
    public function show(FarmStory $farmStory)
    {
        $farmStory->update(['count_view' => $farmStory->count_view + 1]);

        return $this->respondSuccessfully(FarmStoryResource::make($farmStory));
    }
}
