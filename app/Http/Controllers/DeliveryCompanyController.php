<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeliveryCompanyRequest;
use App\Http\Resources\DeliveryCompanyResource;
use App\Models\DeliveryCompany;

class DeliveryCompanyController extends Controller
{
    public function index()
    {
        return DeliveryCompanyResource::collection(DeliveryCompany::all());
    }

    public function store(DeliveryCompanyRequest $request)
    {
        return new DeliveryCompanyResource(DeliveryCompany::create($request->validated()));
    }

    public function show(DeliveryCompany $deliveryCompany)
    {
        return new DeliveryCompanyResource($deliveryCompany);
    }

    public function update(DeliveryCompanyRequest $request, DeliveryCompany $deliveryCompany)
    {
        $deliveryCompany->update($request->validated());

        return new DeliveryCompanyResource($deliveryCompany);
    }

    public function destroy(DeliveryCompany $deliveryCompany)
    {
        $deliveryCompany->delete();

        return response()->json();
    }
}
