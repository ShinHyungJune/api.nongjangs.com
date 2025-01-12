<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return CouponResource::collection(Coupon::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'coupon_group_id' => ['required', 'exists:coupon_groups'],
            'order_id' => ['nullable', 'exists:orders'],
        ]);

        return new CouponResource(Coupon::create($data));
    }

    public function show(Coupon $coupon)
    {
        return new CouponResource($coupon);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'coupon_group_id' => ['required', 'exists:coupon_groups'],
            'order_id' => ['nullable', 'exists:orders'],
        ]);

        $coupon->update($data);

        return new CouponResource($coupon);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response()->json();
    }
}
