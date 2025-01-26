<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PackageSettingRequest;
use App\Http\Resources\PackageSettingResource;
use App\Models\Card;
use App\Models\Delivery;
use App\Models\Package;
use App\Models\PackageSetting;
use Carbon\Carbon;

class PackageSettingController extends ApiController
{
    /** 생성
     * @group 사용자
     * @subgroup PackageSetting(꾸러미 기본설정)
     * @responseFile storage/responses/packageSetting.json
     */
    public function index()
    {
        $items = PackageSetting::where('user_id', auth()->id())->latest()->paginate(30);

        return PackageSettingResource::collection($items);
    }

    /** 생성
     * @group 사용자
     * @subgroup PackageSetting(꾸러미 기본설정)
     * @responseFile storage/responses/packageSetting.json
     */
    public function store(PackageSettingRequest $request)
    {
        $currentPackage = Package::getCurrent();

        if($request->delivery_id) {
            $delivery = Delivery::find($request->delivery_id);

            if($delivery->user_id != auth()->id())
                return $this->respondForbidden();
        }

        if($request->card_id) {
            $card = Card::find($request->card_id);

            if($card->user_id != auth()->id())
                return $this->respondForbidden();
        }

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

    /** 수정
     * @group 사용자
     * @subgroup PackageSetting(꾸러미 기본설정)
     * @responseFile storage/responses/packageSetting.json
     */
    public function update(PackageSettingRequest $request, PackageSetting $packageSetting)
    {
        if($packageSetting->user_id != auth()->id())
            return $this->respondForbidden();

        if($request->type_package)
            $packageSetting->update(['type_package' => $request->type_package]);

        if($request->term_week)
            $packageSetting->update(['term_week' => $request->term_week]);

        if($request->type_package)
            $packageSetting->update(['type_package' => $request->type_package]);

        if($request->type_package)
            $packageSetting->update(['type_package' => $request->type_package]);

        if($request->unlike_material_ids) {
            $materials = [];

            foreach($request->unlike_material_ids as $id){
                $materials[$id] = ['unlike' => 1];
            }

            $packageSetting->materials()->sync($materials);
        }

        if($request->delivery_id) {
            $delivery = Delivery::find($request->delivery_id);

            if($delivery->user_id != auth()->id())
                return $this->respondForbidden();

            $packageSetting->update(['delivery_id' => $request->delivery_id]);
        }

        if($request->card_id) {
            $card = Card::find($request->card_id);

            if($card->user_id != auth()->id())
                return $this->respondForbidden();

            $packageSetting->update(['card_id' => $request->card_id]);
        }

        if($request->name)
            $packageSetting->update(['name' => $request->name]);

        if(isset($request->active))
            $packageSetting->update(['active' => $request->active]);

        return new PackageSettingResource($packageSetting);
    }

}
