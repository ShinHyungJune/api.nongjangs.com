<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CouponGroupRequest;
use App\Http\Resources\CouponGroupResource;
use App\Models\CouponGroup;

class CouponGroupController extends ApiController
{
    public function index()
    {
        $items = CouponGroup::with(['grade']);

        return CouponGroupResource::collection(CouponGroup::all());
    }

    public function store(CouponGroupRequest $request)
    {
        return new CouponGroupResource(CouponGroup::create($request->validated()));
    }

    public function show(CouponGroup $couponGrou)
    {
        return new CouponGroupResource($couponGrou);
    }

    public function update(CouponGroupRequest $request, CouponGroup $couponGrou)
    {
        $couponGrou->update($request->validated());

        return new CouponGroupResource($couponGrou);
    }

    public function destroy(CouponGroup $couponGrou)
    {
        $couponGrou->delete();

        return response()->json();
    }
}
