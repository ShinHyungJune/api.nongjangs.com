<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\VegetableStoryResource;
use App\Http\Requests\VegetableStoryRequest;
use App\Models\VegetableStory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VegetableStoryController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStories.json
     */
    public function index(VegetableStoryRequest $request)
    {
        $items = VegetableStory::where(function($query) use($request){
            $query->whereHas('presetProduct', function ($query) use($request){
                $query->whereHas('preset', function ($query) use($request){
                    $query->whereHas('order', function ($query) use($request){
                        $query->where('payment_id', 'LIKE' ,'%'.$request->word.'%');
                    });
                });
            })->orWhere('description', 'LIKE' ,'%'.$request->word.'%');
        });

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

        $items = $items->latest()->paginate(25);

        return VegetableStoryResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStory.json
     */
    public function show(VegetableStory $vegetableStory)
    {
        return $this->respondSuccessfully(VegetableStoryResource::make($vegetableStory));
    }

    /** 생성
     * @group 관리자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStory.json
     */
    public function store(VegetableStoryRequest $request)
    {
        $createdItem = VegetableStory::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(VegetableStoryResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStory.json
     */
    public function update(VegetableStoryRequest $request, VegetableStory $vegetableStory)
    {
        $vegetableStory->update($request->all());

        if($request->files_remove_ids){
            $medias = $vegetableStory->getMedia("img");

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
                $vegetableStory->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(VegetableStoryResource::make($vegetableStory));
    }

    /** 삭제
     * @group 관리자
     * @subgroup VegetableStory(채소이야기)
     */
    public function destroy(VegetableStoryRequest $request)
    {
        VegetableStory::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
