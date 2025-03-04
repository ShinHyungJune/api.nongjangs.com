<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\FarmStoryResource;
use App\Http\Requests\FarmStoryRequest;
use App\Models\FarmStory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FarmStoryController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup FarmStory(농가이야기)
     * @responseFile storage/responses/farmStories.json
     */
    public function index(FarmStoryRequest $request)
    {
        $items = FarmStory::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        if($request->farm_id)
            $items = $items->where('farm_id', $request->farm_id);

        $items = $items->latest()->paginate(10);

        return FarmStoryResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup FarmStory(농가이야기)
     * @responseFile storage/responses/farmStories.json
     */
    public function show(FarmStory $farmStory)
    {
        return $this->respondSuccessfully(FarmStoryResource::make($farmStory));
    }

    /** 생성
     * @group 관리자
     * @subgroup FarmStory(농가이야기)
     * @responseFile storage/responses/farmStories.json
     */
    public function store(FarmStoryRequest $request)
    {
        $request['tags'] = $request->tags ?? [];

        $createdItem = FarmStory::create($request->validated());

        $createdItem->tags()->sync(array_column($request->tags, 'id'));

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(FarmStoryResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup FarmStory(농가이야기)
     * @responseFile storage/responses/farmStories.json
     */
    public function update(FarmStoryRequest $request, FarmStory $farmStory)
    {
        $request['tags'] = $request->tags ?? [];

        $farmStory->update($request->validated());

        $farmStory->tags()->sync(array_column($request->tags, 'id'));

        if($request->files_remove_ids){
            $medias = $farmStory->getMedia("img");

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
                $farmStory->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(FarmStoryResource::make($farmStory));
    }

    /** 삭제
     * @group 관리자
     * @subgroup FarmStory(농가이야기)
     */
    public function destroy(FarmStoryRequest $request)
    {
        FarmStory::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
