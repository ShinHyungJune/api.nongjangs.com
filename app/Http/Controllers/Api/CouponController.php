<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Models\CouponGroup;
use Illuminate\Http\Request;

class CouponController extends ApiController
{
    /**
    * @group Coupon(쿠폰)
     * @responseFile storage/responses/coupons.json
     */
    public function index()
    {
        $items = auth()->user()->validCoupons()->latest()->paginate(12);

        return CouponResource::collection($items);
    }

    public function store(CouponRequest $request)
    {
        $couponGroup = CouponGroup::find($request->coupon_group_id);

        if(!$couponGroup->isOngoing())
            return $this->respondForbidden("진행중인 이벤트의 쿠폰만 발급 받을 수 있습니다.");

        if($couponGroup->coupons()->where('user_id', auth()->id())->count() > 0)
            return $this->respondForbidden("이미 쿠폰이 발급 완료되었습니다.");

        $coupon = Coupon::create([
            'user_id' => auth()->id(),
            'coupon_group_id' => $couponGroup->id,
        ]);

        return $this->respondSuccessfully();
    }
}
