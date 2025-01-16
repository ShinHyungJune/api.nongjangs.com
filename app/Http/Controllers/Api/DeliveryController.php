<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\DeliveryRequest;
use App\Http\Resources\DeliveryResource;
use App\Models\Delivery;

class DeliveryController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Delivery(배송지)
     * @responseFile storage/responses/deliveries.json
     */
    public function index(DeliveryRequest $request)
    {
        $items = auth()->user()->deliveries()
            ->orderBy('updated_at', 'desc')->paginate(30);

        return DeliveryResource::collection($items);
    }

    /** 생성
     * @group 사용자
     * @subgroup Delivery(배송지)
     * @responseFile storage/responses/delivery.json
     */
    public function store(DeliveryRequest $request)
    {
        if($request->main)
            auth()->user()->deliveries()->update(['main' => 0]);

        $delivery = auth()->user()->deliveries()->create($request->validated());

        return $this->respondSuccessfully(DeliveryResource::make($delivery));
    }

    /** 수정
     * @group 사용자
     * @subgroup Delivery(배송지)
     * @responseFile storage/responses/delivery.json
     */
    public function update(DeliveryRequest $request, Delivery $delivery)
    {
        if($delivery->user_id != auth()->id())
            return $this->respondForbidden();

        if($request->main)
            auth()->user()->deliveries()->update(['main' => 0]);

        $delivery->update($request->validated());

        return $this->respondSuccessfully(DeliveryResource::make($delivery));
    }

    /** 삭제
     * @group 사용자
     * @subgroup Delivery(배송지)
     */
    public function destroy(Delivery $delivery)
    {
        if($delivery->user_id != auth()->id())
            return $this->respondForbidden();

        $delivery->delete();

        return $this->respondSuccessfully();
    }
}
