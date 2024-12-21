<?php

namespace App\Http\Controllers\Api;

use App\Enums\StateOutgoing;
use App\Http\Controllers\Controller;
use App\Http\Requests\RefundRequest;
use App\Http\Resources\RefundResource;
use App\Models\PresetProduct;
use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends ApiController
{
    /** 목록
     * @group Refund(환불)
     * @responseFile storage/responses/refunds.json
     */
    public function index(RefundRequest $request)
    {
        $items = auth()->user()->refunds();

        if($request->preset_product_id)
            $items = $items->where('preset_product_id', $request->preset_product_id);

        $items = $items->latest()->paginate(12);

        return RefundResource::collection($items);
    }

    /** 생성
     * @group Refund(환불)
     * @responseFile storage/responses/refund.json
     */
    public function store(RefundRequest $request)
    {
        $data = $request->validated();

        $presetProduct = PresetProduct::find($request->preset_product_id);

        if(!$presetProduct->can_refund)
            return $this->respondForbidden('교환 요청이 불가능한 출고건입니다.');

        if($presetProduct->refunds()->where('processed_at', null)->count() > 0)
            return $this->respondForbidden('이미 진행중인 환불건이 있습니다.');

        $item = auth()->user()->refunds()->create($data);

        if(is_array($request->file("imgs"))){
            foreach($request->file("imgs") as $file){
                $item->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        return $this->respondSuccessfully(RefundResource::make($item));
    }
}
