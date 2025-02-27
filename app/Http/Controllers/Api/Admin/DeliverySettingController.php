<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeliverySettingResource;
use App\Http\Requests\DeliverySettingRequest;
use App\Models\DeliverySetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeliverySettingController extends ApiController
{
    /** 상세
     * @group 관리자
     * @subgroup DeliverySetting(배송설정)
     */
    public function show()
    {
        $deliverySetting = DeliverySetting::first();

        return $this->respondSuccessfully($deliverySetting ? DeliverySettingResource::make($deliverySetting) : null);
    }

    /** 생성
     * @group 관리자
     * @subgroup DeliverySetting(배송설정)
     */
    public function store(DeliverySettingRequest $request)
    {
        $deliverySetting = DeliverySetting::first();

        $data = $request->validated();

        $data['prices_delivery'] = $request->prices_delivery ? json_encode($request->prices_delivery) : json_encode([]);
        $data['ranges_far_place'] = $request->ranges_far_place ? json_encode($request->ranges_far_place) : json_encode([]);

        if($deliverySetting)
            $deliverySetting->update($data);
        else
            $deliverySetting = DeliverySetting::create($data);

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $deliverySetting->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(DeliverySettingResource::make($deliverySetting));
    }
}
