<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeliverySettingRequest;
use App\Http\Resources\DeliverySettingResource;
use App\Models\DeliverySetting;

class DeliverySettingController extends Controller
{
    public function index()
    {
        return DeliverySettingResource::collection(DeliverySetting::all());
    }

    public function store(DeliverySettingRequest $request)
    {
        return new DeliverySettingResource(DeliverySetting::create($request->validated()));
    }

    public function show(DeliverySetting $deliverySetting)
    {
        return new DeliverySettingResource($deliverySetting);
    }

    public function update(DeliverySettingRequest $request, DeliverySetting $deliverySetting)
    {
        $deliverySetting->update($request->validated());

        return new DeliverySettingResource($deliverySetting);
    }

    public function destroy(DeliverySetting $deliverySetting)
    {
        $deliverySetting->delete();

        return response()->json();
    }
}
