<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponHistoryRequest;
use App\Http\Requests\PointHistoryRequest;
use App\Http\Resources\CouponHistoryResource;
use App\Http\Resources\PointHistoryResource;
use App\Models\CouponHistory;
use Illuminate\Http\Request;

class CouponHistoryController extends Controller
{
    /**
     * @group CouponHistory(쿠폰 내역)
     * @responseFile storage/responses/couponHistories.json
     */
    public function index(CouponHistoryRequest $request)
    {
        $items = auth()->user()->couponHistories();

        if(isset($request->increase))
            $items = $items->where('increase', $request->increase);

        $items = $items->latest()->paginate(12);

        return CouponHistoryResource::collection($items);
    }
}
