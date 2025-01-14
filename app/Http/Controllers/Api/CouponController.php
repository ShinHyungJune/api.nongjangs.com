<?php

namespace App\Http\Controllers\Api;

use App\Enums\TypeCouponGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Models\CouponGroup;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Coupon(쿠폰)
     * @responseFile storage/responses/coupons.json
     */
    public function index(CouponRequest $request)
    {
        $items = auth()->user()->coupons()
            ->where('use', 0);

        if($request->product_id) {
            $product = Product::find($request->product_id);

            $items = $items->whereHas('couponGroup', function ($query) use ($request, $product) {
                $query->whereIn('type', [TypeCouponGroup::ALL, TypeCouponGroup::DELIVERY, TypeCouponGroup::PRODUCT])
                    ->where(function ($query) use ($request, $product) {
                        $query->where('all_product', 1)
                            ->orWhereHas('products', function ($query) use ($request, $product) {
                                $query->where('products.id', $request->product_id);
                            });
                    })->where('min_price_order', '<=', $product->price)
                ->where('started_at', '<=', Carbon::now())
                    ->where('finished_at', '>=', Carbon::now());
            });
        }

        $items = $items->latest()->paginate(100);

        return CouponResource::collection($items);
    }

    /** 생성
     * @group 사용자
     * @subgroup Coupon(쿠폰)
     */
    public function store(CouponRequest $request)
    {
        $couponGroups = CouponGroup::whereIn('id', $request->coupon_group_ids)->get();

        foreach($couponGroups as $couponGroup){
            if($couponGroup->canCreateCoupon(auth()->user()))
                auth()->user()->coupons()->create([
                    'coupon_group_id' => $couponGroup->id
                ]);
        }

        return $this->respondSuccessfully();
    }
}
