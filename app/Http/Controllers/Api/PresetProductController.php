<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatePresetProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\PresetProductRequest;
use App\Http\Resources\PresetProductResource;
use App\Models\Coupon;
use App\Models\PresetProduct;

class PresetProductController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup PresetProduct(출고상품)
     * @responseFile storage/responses/presetProducts.json
     */
    public function index(PresetProductRequest $request)
    {
        $items = auth()->user()->presetProducts();

        if($request->can_review)
            $items = $items->where('state', StatePresetProduct::CONFIRMED)->whereDoesntHave('review');

        if($request->can_vegetable_story)
            $items = $items->where('state', StatePresetProduct::CONFIRMED);

        $items = $items->latest()->paginate(20);

        return PresetProductResource::collection($items);
    }

    /** 쿠폰적용
     * @group 사용자
     * @subgroup PresetProduct(출고상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function updateCoupon(PresetProductRequest $request, PresetProduct $presetProduct)
    {
        if(!$presetProduct->preset->can_order)
            return $this->respondForbidden('쿠폰을 적용할 수 없습니다.');

        $coupon = Coupon::find($request->coupon_id);

        $coupons = auth()->user()->canUseCoupons($presetProduct);

        if(!$coupons->where('coupons.id', $coupon->id)->first())
            return $this->respondForbidden('해당 상품에 적용할 수 없는 쿠폰입니다.');

        $presetProduct->update([
            'coupon_id' => $coupon->id,
            'price_coupon' => $presetProduct->calculatePriceCoupon($coupon),
        ]);

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

}
