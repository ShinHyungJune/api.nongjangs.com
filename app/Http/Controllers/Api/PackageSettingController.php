<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PackageSettingRequest;
use App\Http\Resources\PackageSettingResource;
use App\Models\Card;
use App\Models\Delivery;
use App\Models\Package;
use App\Models\PackageSetting;
use App\Models\Preset;
use Carbon\Carbon;

class PackageSettingController extends ApiController
{

    /** 목록
     * @group 사용자
     * @subgroup PackageSetting(꾸러미 기본설정)
     * @responseFile storage/responses/packageSetting.json
     */
    public function index()
    {
        $items = PackageSetting::where('user_id', auth()->id())->latest()->paginate(30);

        return PackageSettingResource::collection($items);
    }

    /** 상세
     * @group 사용자
     * @subgroup PackageSetting(꾸러미 기본설정)
     * @responseFile storage/responses/packageSetting.json
     */
    public function show()
    {
        $item = PackageSetting::where('user_id', auth()->id())->latest()->first();

        return $this->respondSuccessfully($item ? PackageSettingResource::make($item) : '');
    }

    /** 생성
     * @group 사용자
     * @subgroup PackageSetting(꾸러미 기본설정)
     * @responseFile storage/responses/packageSetting.json
     */
    public function store(PackageSettingRequest $request)
    {

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
        ], $request->validated()));

        $packageSetting = auth()->user()->packageSetting;

        if($request->unlike_material_ids) {
            $materials = [];

            foreach($request->unlike_material_ids as $id){
                $materials[$id] = ['unlike' => 1];
            }

            $packageSetting->materials()->sync($materials);
        }

        return $this->respondSuccessfully(PackageSettingResource::make($packageSetting));
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

        if(isset($request->active)) {
            $packageSetting->update([
                'active' => $request->active,
                'reason' => $request->reason ?? null,
                'and_so_on' => $request->and_so_on ?? null,
                'memo' => $request->memo ?? null,
            ]);
        }

        return $this->respondSuccessfully(PackageSettingResource::make($packageSetting));
    }

    /** 수정
     * @group 사용자
     * @subgroup PackageSetting(꾸러미 기본설정)
     * @responseFile storage/responses/packageSetting.json
     */
    public function updateUnlikeMaterials(PackageSettingRequest $request, PackageSetting $packageSetting)
    {
        $materials = [];

        if(is_array($request->unlike_material_ids)) {
            foreach($request->unlike_material_ids as $id){
                $materials[$id] = ['unlike' => 1];
            }
        }

        $packageSetting->materials()->sync($materials);

        return $this->respondSuccessfully(PackageSettingResource::make($packageSetting));
    }


}
