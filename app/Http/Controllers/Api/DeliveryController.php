<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\DeliveryRequest;
use App\Http\Resources\DeliveryResource;
use App\Models\Delivery;

class DeliveryController extends ApiController
{
    public function index()
    {
        return DeliveryResource::collection(Delivery::all());
    }

    public function store(DeliveryRequest $request)
    {
        return new DeliveryResource(Delivery::create($request->validated()));
    }

    public function show(Delivery $delivery)
    {
        return new DeliveryResource($delivery);
    }

    public function update(DeliveryRequest $request, Delivery $delivery)
    {
        $delivery->update($request->validated());

        return new DeliveryResource($delivery);
    }

    public function destroy(Delivery $delivery)
    {
        $delivery->delete();

        return response()->json();
    }
}
