<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Models\PresetProduct;
use Carbon\Carbon;

class PackageController extends ApiController
{
    public function index(PackageRequest $request)
    {
        $items = Package::getCanOrders();

        if($request->can_fast && $request->preset_product_id) {
            $presetProduct = PresetProduct::find($request->preset_product_id);

            if($presetProduct && $presetProduct->package) {
                $items = $items->where('count', '<', $presetProduct->package->count);
            }
        }

        if($request->can_late && $request->preset_product_id) {
            $presetProduct = PresetProduct::find($request->preset_product_id);

            if($presetProduct && $presetProduct->package) {
                $items = $items->where('count', '>', $presetProduct->package->count);
            }
        }

        $items = $items->orderBy('count', 'asc')->paginate(30);

        return PackageResource::collection($items);
    }

    /** 상세
     * @group 사용자
     * @subgroup Package(꾸러미)
     * @responseFile storage/responses/package.json
     */
    public function show(Package $package)
    {
        return $this->respondSuccessfully(PackageResource::make($package));
    }

    /** 현재 구매 가능한 꾸러미 회차 상세
     * @group 사용자
     * @subgroup Package(꾸러미)
     * @responseFile storage/responses/package.json
     */
    public function canOrder()
    {
        $findPackage = Package::getCanOrder();

        if(!$findPackage)
            return $this->respondForbidden('현재 구매할 수 있는 회차가 없습니다.');

        return $this->respondSuccessfully(PackageResource::make($findPackage));
    }

}
