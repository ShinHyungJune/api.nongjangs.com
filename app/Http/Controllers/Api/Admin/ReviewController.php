<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends ApiController
{
    /** 통계
     * @group 관리자
     * @subgroup Review(리뷰)
     * @responseFile storage/responses/reviewsCounts.json
     */
    public function counts(ReviewRequest $request)
    {
        $item = [
            'package' => [
                'average_score' => Review::whereNotNull('package_id')->average('score'),
                'count' => Review::whereNotNull('package_id')->count(),
                'count_best' => Review::whereNotNull('package_id')->where('best', 1)->count(),
                'count_wait' => Review::whereNotNull('package_id')->where('reply', null)->count(),
            ],

            'product' => [
                'average_score' => Review::whereNotNull('product_id')->average('score'),
                'count' => Review::whereNotNull('product_id')->count(),
                'count_best' => Review::whereNotNull('product_id')->where('best', 1)->count(),
                'count_wait' => Review::whereNotNull('product_id')->where('reply', null)->count(),
            ],
        ];

        return $this->respondSuccessfully($item);
    }

    /** 목록
     * @group 관리자
     * @subgroup Review(리뷰)
     * @responseFile storage/responses/reviews.json
     */
    public function index(ReviewRequest $request)
    {
        $items = Review::where(function($query) use($request){
            $query->whereHas('presetProduct',function ($query) use($request){
                $query->whereHas('preset', function ($query) use ($request){
                    $query->whereHas('order', function ($query) use($request) {
                        $query->where('payment_id', 'LIKE', '%' . $request->word . '%')
                            ->orWhere('user_name', 'LIKE', '%'.$request->word.'%');
                    });
                });
            })->orWhere('description', 'LIKE' ,'%'.$request->word.'%');
        });

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

        if($request->has_column)
            $items = $items->whereNotNull($request->has_column);

        if(isset($request->best))
            $items = $items->where('best', $request->best);

        if(isset($request->reply)){
            if($request->reply)
                $items = $items->whereNotNull('reply');
            else
                $items = $items->whereNull('reply');
        }

        $items = $items->latest()->paginate(25);

        return ReviewResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Review(리뷰)
     * @responseFile storage/responses/reviews.json
     */
    public function show(Review $review)
    {
        return $this->respondSuccessfully(ReviewResource::make($review));
    }

    /** 생성
     * @group 관리자
     * @subgroup Review(리뷰)
     * @responseFile storage/responses/reviews.json
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
     * @responseFile storage/responses/reviews.json
     */
    public function update(ReviewRequest $request, Review $review)
    {
        $review->update(array_merge($request->validated(), [
            'reply_at' => Carbon::now()
        ]));

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
     */
    public function destroy(ReviewRequest $request)
    {
        Review::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
