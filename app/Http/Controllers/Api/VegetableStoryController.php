<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\VegetableStoryRequest;
use App\Http\Resources\VegetableStoryResource;
use App\Models\VegetableStory;

class VegetableStoryController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStories.json
     */
    public function index()
    {
        return VegetableStoryResource::collection(VegetableStory::all());
    }

    /** 생성
     * @group 사용자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStory.json
     */
    public function store(VegetableStoryRequest $request)
    {
        return new VegetableStoryResource(VegetableStory::create($request->validated()));
    }

    /** 수정
     * @group 사용자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStory.json
     */
    public function update(VegetableStoryRequest $request, VegetableStory $vegetableStory)
    {
        $vegetableStory->update($request->validated());

        return new VegetableStoryResource($vegetableStory);
    }

    /** 삭제
     * @group 사용자
     * @subgroup VegetableStory(채소이야기)
     */
    public function destroy(VegetableStory $vegetableStory)
    {
        $vegetableStory->delete();

        return response()->json();
    }
}
