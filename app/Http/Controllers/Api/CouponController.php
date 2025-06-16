<?php

namespace App\Http\Controllers\Api;

use App\Enums\TypeCouponGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Models\CouponGroup;
use App\Models\Preset;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Coupon(쿠폰)
     * @responseFile storage/responses/coupons.json
     */
    public function index(CouponRequest $request)
    {
        if($request->preset_id)
            $preset = Preset::find($request->preset_id);

        $items = auth()->user()->canUseCoupons($preset ?? null);

        if($request->order_by == 'value')
            $items = $items->join('coupon_groups', 'coupons.coupon_group_id', '=', 'coupon_groups.id')
                ->orderBy('coupon_groups.value', 'desc')
                ->select('coupons.*');

        if($request->order_by == 'created_at')
            $items = $items->orderBy($request->order_by, 'desc');

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
                    'coupon_group_id' => $couponGroup->id,
                ]);
        }

        return $this->respondSuccessfully();
    }
}
