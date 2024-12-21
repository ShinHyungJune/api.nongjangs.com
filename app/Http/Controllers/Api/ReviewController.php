<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends ApiController
{
    /**
     * @group Review(리뷰)
     * @responseFile storage/responses/reviews.json
     * */
    public function index(ReviewRequest $request)
    {
        $items = new Review();

        $request['take'] = $request->take ?? 12;
        $request['order_by'] = $request->order_by ?? 'best';
        $request['align'] = $request->align ?? 'desc';

        if($request->product_id)
            $items = $items->where('product_id', $request->product_id);

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

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

        if(!$presetProduct->can_review)
            return $this->respondForbidden('리뷰를 작성할 수 없습니다.');

        $product = Product::withTrashed()->find($presetProduct->product_id);

        if(!$product)
            return $this->respondForbidden('더 이상 판매되지 않은 상품에 리뷰를 작성할 수 없습니다.');

        $review = auth()->user()->reviews()->create([
            'preset_product_id' => $presetProduct->id,
            'product_id' => $product->id,
            'description' => $request->description,
            'score' => $request->score,
            'point' => Review::$point,
        ]);

        if(is_array($request->file("imgs"))){
            foreach($request->file("imgs") as $file){
                $review->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        $review->load("media");

        $review->update(['photo' => $review->hasMedia("imgs")]);

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

        $review->update(['photo' => $review->hasMedia("imgs")]);

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
