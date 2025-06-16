<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PresetRequest;
use App\Http\Resources\PresetResource;
use App\Models\Coupon;
use App\Models\Option;
use App\Models\Preset;

class PresetController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Preset(상품조합)
     * @responseFile storage/responses/preset.json
     */
    public function store(PresetRequest $request)
    {
        $preset = auth()->user()->presets()->create();

        $result = $preset->attachProducts($request->all(), $request->package_id ? auth()->user()->packageSetting : null);

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        return $this->respondSuccessfully(PresetResource::make($preset));
    }

    /** 상세
     * @group 사용자
     * @subgroup Preset(상품조합)
     * @responseFile storage/responses/preset.json
     */
    public function update(PresetRequest $request, Preset $preset)
    {
        if(!$preset->can_order)
            return $this->respondForbidden('수정할 수 없습니다.');

        $result = $preset->attachProducts($request->all());

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        return $this->respondSuccessfully(PresetResource::make($preset));
    }

    /** 쿠폰적용
     * @group 사용자
     * @subgroup Preset(상품조합)
     * @responseFile storage/responses/preset.json
     */
    public function updateCoupon(PresetRequest $request, Preset $preset)
    {
        if(!$preset->can_order)
            return $this->respondForbidden('쿠폰을 적용할 수 없습니다.');

        if(!$request->coupon_id) {
            $preset->update([
                'coupon_id' => null,
                'price_coupon' => 0,
            ]);

            return $this->respondSuccessfully(PresetResource::make($preset));
        }

        $coupon = Coupon::find($request->coupon_id);

        $coupons = auth()->user()->canUseCoupons($preset);

        if(!$coupons->where('coupons.id', $coupon->id)->first())
            return $this->respondForbidden('해당 상품에 적용할 수 없는 쿠폰입니다.');

        foreach ($preset->presetProducts as $presetProduct) {
            if ($presetProduct->product && !$presetProduct->product->can_use_coupon) {
                return $this->respondForbidden('쿠폰적용불가 상품이 포함되어 있습니다.');
            }
        }

        $preset->update([
            'coupon_id' => $coupon->id,
            'price_coupon' => $preset->calculatePriceCoupon($coupon),
        ]);

        return $this->respondSuccessfully(PresetResource::make($preset));
    }
}
