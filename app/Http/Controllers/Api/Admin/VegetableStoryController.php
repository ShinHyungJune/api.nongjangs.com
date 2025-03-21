<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\VegetableStoryResource;
use App\Http\Requests\VegetableStoryRequest;
use App\Models\Review;
use App\Models\VegetableStory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VegetableStoryController extends ApiController
{
    /** 통계
     * @group 관리자
     * @subgroup VegetableStory(채소이야기)
     * @responseFile storage/responses/vegetableStoriesCounts.json
     */
    public function counts(ReviewRequest $request)
    {
        $item = [
            'count' => VegetableStory::count(),
            'count_package' => VegetableStory::whereNotNull('package_id')->count(),
            'count_product' => VegetableStory::whereNotNull('product_id')->count(),
        ];

        return $this->respondSuccessfully($item);
    }

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
                        $query->where('merchant_uid', 'LIKE' ,'%'.$request->word.'%')
                            ->orWhere('user_name', 'LIKE', '%'.$request->word.'%');
                    });
                });
            })->orWhere('description', 'LIKE' ,'%'.$request->word.'%');
        });

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

        if($request->has_column)
            $items = $items->whereNotNull( $request->has_column);

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
