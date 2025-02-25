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
     * @responseFile storage/responses/deliverySetting.json
     */
    public function show()
    {
        $deliverySetting = DeliverySetting::first();

        return $this->respondSuccessfully($deliverySetting ? DeliverySettingResource::make($deliverySetting) : null);
    }

    /** 생성
     * @group 관리자
     * @subgroup DeliverySetting(배송설정)
     * @responseFile storage/responses/deliverySetting.json
     */
    public function store(DeliverySettingRequest $request)
    {
        $createdItem = DeliverySetting::create($request->validated());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(DeliverySettingResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup DeliverySetting(배송설정)
     * @responseFile storage/responses/deliverySetting.json
     */
    public function update(DeliverySettingRequest $request, DeliverySetting $deliverySetting)
    {
        $deliverySetting->update($request->all());

        if($request->files_remove_ids){
            $medias = $deliverySetting->getMedia("img");

            foreach($medias as $media){
                foreach($request->files_remove_ids as $id){
                    if((int) $media->id == (int) $id){
                        $media->delete();
                    }
                }
            }
        }

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $deliverySetting->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(DeliverySettingResource::make($deliverySetting));
    }

    /** 삭제
     * @group 관리자
     * @subgroup DeliverySetting(배송설정)
     */
    public function destroy(DeliverySettingRequest $request)
    {
        DeliverySetting::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
