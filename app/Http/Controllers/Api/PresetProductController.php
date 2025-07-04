<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatePresetProduct;
use App\Enums\TypePackageChangeHistory;
use App\Http\Controllers\Controller;
use App\Http\Requests\PresetProductRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PresetProductResource;
use App\Jobs\CheckDeliveryStateJob;
use App\Models\Coupon;
use App\Models\DeliveryTracker;
use App\Models\Order;
use App\Models\Package;
use App\Models\PresetProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

    /** 상세
     * @group 사용자
     * @subgroup PresetProduct(출고상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function show(PresetProduct $presetProduct, PresetProductRequest $request)
    {
        if($presetProduct->preset->user_id != auth()->id())
            return $this->respondForbidden();

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }


    /** 현재 대상 꾸러미 회차출고 상세
     * @group 사용자
     * @subgroup PresetProduct(출고상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function ongoingPackagePresetProduct()
    {
        $presetProduct = auth()->user()->ongoingPackagePresetProducts()->first();

        $packageSetting = auth()->user()->packageSetting;

        if(!$presetProduct){
            // 구독 안하고 있음
            if(!$packageSetting || !$packageSetting->active)
                return $this->respondSuccessfully(null);

            // 가장 최근
            $presetProduct = auth()->user()->presetProducts()->whereNotNull('package_id')
                ->orderBy('id', 'desc')
                ->first();
        }

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 취소
     * @group 사용자
     * @subgroup PresetProduct(출고상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function cancel(PresetProduct $presetProduct)
    {
        if(!$presetProduct->can_cancel)
            return $this->respondForbidden('취소 불가능한 상태입니다.');

        $result = $presetProduct->cancel();

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 취소요청
     * @group 사용자
     * @subgroup PresetProduct(출고상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function requestCancel(PresetProduct $presetProduct, PresetProductRequest $request)
    {
        if(!$presetProduct->can_request_cancel)
            return $this->respondForbidden('취소 불가능한 상태입니다.');

        $result = $presetProduct->update([
            'state' => StatePresetProduct::REQUEST_CANCEL,
            'state_origin' => $presetProduct->state,
            'request_cancel_at' => Carbon::now(),
            'reason_request_cancel' => $request->reason_request_cancel,
        ]);

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 취소요청 번복
     * @group 사용자
     * @subgroup PresetProduct(출고상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function recoverCancel(PresetProduct $presetProduct, PresetProductRequest $request)
    {
        if($presetProduct->preset->user_id != auth()->id())
            return $this->respondForbidden('권한이 없습니다.');

        if($presetProduct->state != StatePresetProduct::REQUEST_CANCEL)
            return $this->respondForbidden('취소요청 상태일 때만 취소를 회수할 수 있습니다.');

        $presetProduct->update([
            'state' => $presetProduct->state_origin,
        ]);

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 구매확정
     * @group 사용자
     * @subgroup PresetProduct(출고상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function confirm(PresetProduct $presetProduct, PresetProductRequest $request)
    {
        if(!$presetProduct->can_confirm)
            return $this->respondForbidden('구매확정할 수 없습니다.');

        $presetProduct->update([
            'state' => StatePresetProduct::CONFIRMED,
            'confirm_at' => Carbon::now(),
        ]);

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 현재 사용자가 모니터링해야할 꾸러미출고
     * @group 사용자
     * @subgroup PresetProduct(출고상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function currentPackage(PresetProductRequest $request)
    {
        $presetProduct = auth()->user()->getCurrentPackagePresetProduct();

        return $this->respondSuccessfully($presetProduct ? PresetProductResource::make($presetProduct) : null);
    }

    /** 꾸러미 구성품목 변경
     * @group 사용자
     * @subgroup PresetProduct(출고상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function updateMaterials(PresetProduct $presetProduct, PresetProductRequest $request)
    {
        if(!$presetProduct->can_update_materials)
            return $this->respondForbidden('품목구성을 변경할 수 없습니다.');

        $result = $presetProduct->syncMaterials($request->materials);

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 일정변경
     * @group 사용자
     * @subgroup PresetProduct(출고상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function change(PresetProduct $presetProduct, PresetProductRequest $request)
    {
        $package = Package::getCanOrders()->where('id', $request->package_id)->first();

        if(!$package) {
            Log::info('패키지 변경불가', [
                'package_id' => $request->package_id,
                'presetProduct' => $presetProduct
            ]);

            return $this->respondForbidden('변경 가능한 패키지가 아닙니다.');
        }

        $presetProduct->changePackage($package, $package->count >= $presetProduct->package->count ? TypePackageChangeHistory::LATE : TypePackageChangeHistory::FAST);;

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    public function updateDelivery(PresetProduct $presetProduct)
    {
        dispatch(new CheckDeliveryStateJob($presetProduct));

        return $this->respondSuccessfully();
    }
}
