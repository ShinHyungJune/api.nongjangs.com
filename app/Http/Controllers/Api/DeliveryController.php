<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryRequest;
use App\Http\Resources\DeliveryResource;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends ApiController
{
    /**
     * @group Delivery(배송지)
     * @responseFile storage/responses/deliveries.json
     */
    public function index(DeliveryRequest $request)
    {
        $items = auth()->user()->deliveries();

        $items = $items->orderBy('main', 'desc')->latest()->paginate(30);

        return DeliveryResource::collection($items);
    }

    /**
     * @group Delivery(배송지)
     * @responseFile storage/responses/delivery.json
     */
    public function show(Delivery $delivery)
    {
        if($delivery->user_id != auth()->id())
            return $this->respondForbidden();

        return $this->respondSuccessfully(DeliveryResource::make($delivery));
    }

    /**
     * @group Delivery(배송지)
     * @responseFile storage/responses/delivery.json
     */
    public function store(DeliveryRequest $request)
    {
        $data = $request->validated();

        $delivery = auth()->user()->deliveries()->create($data);

        return $this->respondSuccessfully(DeliveryResource::make($delivery));
    }

    /**
     * @group Delivery(배송지)
     * @responseFile storage/responses/delivery.json
     */
    public function update(DeliveryRequest $request, Delivery $delivery)
    {
        if($delivery->user_id != auth()->id())
            return $this->respondForbidden();

        $data = $request->validated();

        $delivery->update($data);

        return $this->respondSuccessfully(DeliveryResource::make($delivery));
    }

    /**
     * @group Delivery(배송지)
     */
    public function destroy(Delivery $delivery)
    {
        if($delivery->user_id != auth()->id())
            return $this->respondForbidden();

        $delivery->delete();

        return $this->respondSuccessfully();
    }
}
