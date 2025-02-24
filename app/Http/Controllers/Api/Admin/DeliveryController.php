<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryResource;
use App\Http\Requests\DeliveryRequest;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeliveryController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Delivery(배송지)
     * @responseFile storage/responses/deliveries.json
     */
    public function index(DeliveryRequest $request)
    {
        $items = Delivery::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

        $items = $items->latest()->paginate(10);

        return DeliveryResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Delivery(배송지)
     * @responseFile storage/responses/deliveries.json
     */
    public function show(Delivery $delivery)
    {
        return $this->respondSuccessfully(DeliveryResource::make($delivery));
    }

    /** 생성
     * @group 관리자
     * @subgroup Delivery(배송지)
     * @responseFile storage/responses/deliveries.json
     */
    public function store(DeliveryRequest $request)
    {
        $createdItem = Delivery::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(DeliveryResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Delivery(배송지)
     * @responseFile storage/responses/deliveries.json
     */
    public function update(DeliveryRequest $request, Delivery $delivery)
    {
        $delivery->update($request->all());

        if($request->files_remove_ids){
            $medias = $delivery->getMedia("img");

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
                $delivery->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(DeliveryResource::make($delivery));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Delivery(배송지)
     */
    public function destroy(DeliveryRequest $request)
    {
        Delivery::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
