<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\DeliveryCompanyRequest;
use App\Http\Resources\DeliveryCompanyResource;
use App\Models\DeliveryCompany;

class DeliveryCompanyController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup DeliveryCompany(배송사)
     * @responseFile storage/responses/deliveryCompanies.json
     */
    public function index(DeliveryCompanyRequest $request)
    {
        $items = new DeliveryCompany();

        if($request->word)
            $items = $items->where('title', 'like', '%' . $request->word . '%')
                          ->orWhere('code', 'like', '%' . $request->word . '%');

        if(isset($request->use))
            $items = $items->where('use', $request->use);

        $items = $items->latest()->paginate(30);

        return DeliveryCompanyResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup DeliveryCompany(배송사)
     * @responseFile storage/responses/deliveryCompany.json
     */
    public function show(DeliveryCompany $deliveryCompany)
    {
        return $this->respondSuccessfully(DeliveryCompanyResource::make($deliveryCompany));
    }

    /** 생성
     * @group 관리자
     * @subgroup DeliveryCompany(배송사)
     * @responseFile storage/responses/deliveryCompany.json
     */
    public function store(DeliveryCompanyRequest $request)
    {
        $createdItem = DeliveryCompany::create($request->all());

        return $this->respondSuccessfully(DeliveryCompanyResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup DeliveryCompany(배송사)
     * @responseFile storage/responses/deliveryCompany.json
     */
    public function update(DeliveryCompanyRequest $request, DeliveryCompany $deliveryCompany)
    {
        $deliveryCompany->update($request->all());

        return $this->respondSuccessfully(DeliveryCompanyResource::make($deliveryCompany));
    }

    /** 삭제
     * @group 관리자
     * @subgroup DeliveryCompany(배송사)
     */
    public function destroy(DeliveryCompanyRequest $request)
    {
        DeliveryCompany::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
