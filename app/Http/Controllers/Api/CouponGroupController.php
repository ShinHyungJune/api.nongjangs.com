<?php

namespace App\Http\Controllers\Api;

use App\Enums\TypeCouponGroup;
use App\Enums\TypeExpire;
use App\Http\Requests\CouponGroupRequest;
use App\Http\Resources\CouponGroupResource;
use App\Models\CouponGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class CouponGroupController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup CouponGroup(쿠폰그룹)
     * @responseFile storage/responses/couponGroups.json
     */
    public function index(CouponGroupRequest $request)
    {
        $request['order_by'] = $request->order_by ?? "created_at";

        $items = CouponGroup::where('moment', null);

        $types = [TypeCouponGroup::ALL, TypeCouponGroup::DELIVERY];

        if($request->product_id) {
            $types = array_merge($types, [TypeCouponGroup::PRODUCT]);

            $items = $items->where(function (Builder $query) use($request, $types){
                $query->whereHas('products', function($query) use($request){
                    $query->where('products.id', $request->product_id);
                })->orWhere('all_product', 1)
                    ->orWhereIn('type', [TypeCouponGroup::ALL, TypeCouponGroup::DELIVERY]);
            });
        }

        $items = $items->whereIn('type', $types);

        $items = $items->where(function (Builder $query) {
            $query->where('type_expire', TypeExpire::FROM_DOWNLOAD)
                ->orWhere(function (Builder $query) {
                    $query->where('type_expire', TypeExpire::SPECIFIC)
                        ->where('started_at', '<=', Carbon::now())
                        ->where('finished_at', '>=', Carbon::now());
                });
        });

        if($request->can_download)
            $items = $items->whereDoesntHave('coupons', function ($query){
                $query->where('user_id', auth()->id());
            });

        $items = $items->orderBy($request->order_by, 'desc')->paginate(100);

        return CouponGroupResource::collection($items);
    }
}
