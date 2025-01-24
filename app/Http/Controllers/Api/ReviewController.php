<?php

namespace App\Http\Controllers\Api;

use App\Enums\TypePointHistory;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\PresetProduct;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends ApiController
{
    /**
     * @group Review(리뷰)
     * @responseFile storage/responses/reviews.json
     * */
    public function index(ReviewRequest $request)
    {
        $items = Review::withCount('likes as count_like');

        $request['take'] = $request->take ?? 12;
        $request['order_by'] = $request->order_by ?? 'best';
        $request['align'] = $request->align ?? 'desc';

        if($request->product_id)
            $items = $items->where('product_id', $request->product_id);

        if($request->package_id)
            $items = $items->where('package_id', $request->package_id);

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

        if($request->has_column)
            $items = $items->whereNotNull($request->has_column);

        if($request->photo)
            $items = $items->where('photo', $request->photo);

        $items = $items->orderBy($request->order_by, $request->align)->paginate($request->take);

        return ReviewResource::collection($items);
    }

    /**
     * @group Review(리뷰)
     * @responseFile storage/responses/review.json
     * */
    public function store(ReviewRequest $request)
    {
        $presetProduct = PresetProduct::find($request->preset_product_id);

        $data = $request->validated();

        if(!$presetProduct->can_review)
            return $this->respondForbidden('리뷰를 작성할 수 없습니다.');

        $review = auth()->user()->reviews()->create($data);

        if(is_array($request->file("imgs"))){
            foreach($request->file("imgs") as $file){
                $review->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        $review->load("media");

        $photo = $review->hasMedia("imgs");

        $review->update(['photo' => $photo]);

        $point = $photo ? Review::$pointPhoto : Review::$pointText;

        auth()->user()->update([
            'point' => auth()->user()->point + $point,
        ]);

        auth()->user()->pointHistories()->create([
            'point_current' => auth()->user()->point,
            'point' => $point,
            'increase' => 1,
            'type' => $photo ? TypePointHistory::PHOTO_REVIEW_CREATED : TypePointHistory::TEXT_REVIEW_CREATED,
        ]);

        return $this->respondSuccessfully(ReviewResource::make($review));
    }

    /**
     * @group Review(리뷰)
     * @responseFile storage/responses/review.json
     * */
    public function update(ReviewRequest $request, Review $review)
    {
        if($review->user_id != auth()->id())
            return $this->respondForbidden();

        $review->update([
            'score' => $request->score,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if($request->imgs_remove_ids){
            $medias = $review->getMedia("imgs");

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
                $review->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        $review->load("media");

        $photo = $review->hasMedia("imgs");

        $review->update(['photo' => $photo]);

        return $this->respondSuccessfully(ReviewResource::make($review));
    }

    /**
     * @group Review(리뷰)
     * */
    public function destroy(Review $review)
    {
        if($review->user_id != auth()->id())
            return $this->respondForbidden();

        $review->delete();

        return $this->respondSuccessfully();
    }
}
