<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Review(리뷰)
     * @priority 13
     * @responseFile storage/responses/reviews.json
     */
    public function index(ReviewRequest $request)
    {
        $items = Review::where(function($query) use($request){
            $query->whereHas('user', function ($query) use($request){
                $query->where('name', 'LIKE' ,'%'.$request->word.'%');
            })->orWhereHas('product', function ($query) use($request){
                $query->where('title', 'LIKE' ,'%'.$request->word.'%');
            });
        });

        $items = $items->latest()->paginate(10);

        return ReviewResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Review(리뷰)
     * @priority 13
     * @responseFile storage/responses/review.json
     */
    public function show(Review $review)
    {
        return $this->respondSuccessfully(ReviewResource::make($review));
    }

    /** 생성
     * @group 관리자
     * @subgroup Review(리뷰)
     * @priority 13
     * @responseFile storage/responses/review.json
     */
    public function store(ReviewRequest $request)
    {
        $createdItem = Review::create(array_merge($request->all(), ['user_id' => auth()->id()]));

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        return $this->respondSuccessfully(ReviewResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Review(리뷰)
     * @priority 13
     * @responseFile storage/responses/review.json
     */
    public function update(ReviewRequest $request, Review $review)
    {
        $review->update($request->all());

        if($request->files_remove_ids){
            $medias = $review->getMedia("imgs");

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
                $review->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        return $this->respondSuccessfully(ReviewResource::make($review));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Review(리뷰)
     * @priority 13
     */
    public function destroy(ReviewRequest $request)
    {
        Review::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
