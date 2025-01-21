<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PackageSettingRequest;
use App\Http\Resources\PackageSettingResource;
use App\Models\Package;
use App\Models\PackageSetting;
use Carbon\Carbon;

class PackageSettingController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup PackageSetting(꾸러미 기본설정)
     * @responseFile storage/responses/packageSetting.json
     */
    public function store(PackageSettingRequest $request)
    {
        $currentPackage = Package::getCurrent();

        PackageSetting::updateOrCreate([
            'user_id' => auth()->id(),
        ],array_merge([
            'user_id' => auth()->id(),
            'first_package_id' => $request->active && $currentPackage ? $currentPackage->id : null,
            'will_order_at' => $request->active && $currentPackage ? Carbon::make($currentPackage->will_delivery_at)->subDays(2)->setHours(0)->setSeconds(0) : null,
        ], $request->validated()));

        $item = auth()->user()->packageSetting;

        if($request->unlike_material_ids) {
            $materials = [];

            foreach($request->unlike_material_ids as $id){
                $materials[$id] = ['unlike' => 1];
            }

            $item->materials()->sync($materials);
        }

        return $this->respondSuccessfully(PackageSettingResource::make($item));
    }

    public function update(PackageSettingRequest $request, PackageSetting $packageSetting)
    {
        $packageSetting->update($request->validated());

        return new PackageSettingResource($packageSetting);
    }

}
