<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatePresetProduct;
use App\Http\Requests\PresetProductRequest;
use App\Http\Requests\PresetRequest;
use App\Http\Resources\PresetProductMiniResource;
use App\Http\Resources\PresetProductResource;
use App\Http\Resources\PresetResource;
use App\Models\Preset;
use App\Models\PresetProduct;
use Illuminate\Support\Arr;

class PresetProductController extends ApiController
{
    /** 목록
     * @group PresetProduct(연관상품)
     * @responseFile storage/responses/presetProducts.json
     */
    public function index(PresetProductRequest $request)
    {
        if(!auth()->user() && !$request->guest_id)
            return $this->respondForbidden('비회원이라면 고유번호가 필요합니다. 관리자에게 문의하세요.');

        $items = auth()->user() ? auth()->user()->presetProducts() : PresetProduct::whereHas('preset', function ($query) use($request){
            $query->where('guest_id', $request->guest_id);
        });

        if(isset($request->additional))
            $items = $items->where('additional', $request->additional);

        if($request->states_except)
            $items = $items->whereNotIn('state', $request->states_except);

        if($request->can_review)
            $items = $items->whereDoesntHave('review')
                ->whereIn('state', [StatePresetProduct::DELIVERED, StatePresetProduct::CONFIRMED]);

        $items = $items->latest()->paginate(12);

        return PresetProductResource::collection($items);
    }

    /** 상세
     * @group PresetProduct(연관상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function show($uuid)
    {
        $presetProduct = PresetProduct::where('uuid', $uuid)->first();

        if(!$presetProduct)
            return $this->respondNotFound();

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 수정
     * @group PresetProduct(연관상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function update(PresetProductRequest $request, $uuid)
    {
        $data = $request->validated();

        $presetProduct = PresetProduct::where('uuid', $uuid)->first();

        if(!$presetProduct)
            return $this->respondNotFound();

        if($presetProduct->state != StatePresetProduct::BEFORE_PAYMENT && $presetProduct->state != StatePresetProduct::READY)
            return $this->respondForbidden('시안제작단계 이후부터는 요청사항을 수정할 수 없습니다.');

        if($request->description || is_array($request->file('sheet')))
            $data['submit_request'] = true;

        $presetProduct->update(Arr::except($data, ['sheet', 'files']));

        if(is_array($request->file("sheet"))){
            foreach($request->file("sheet") as $file){
                $presetProduct->addMedia($file["file"])->toMediaCollection("sheet", "s3");
            }
        }

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $presetProduct->addMedia($file["file"])->toMediaCollection("files", "s3");
            }
        }
        /*if(is_array($request->file("logo"))){
            foreach($request->file("logo") as $file){
                $presetProduct->addMedia($file["file"])->toMediaCollection("logo", "s3");
            }
        }

        if(is_array($request->file("stamp"))){
            foreach($request->file("stamp") as $file){
                $presetProduct->addMedia($file["file"])->toMediaCollection("stamp", "s3");
            }
        }*/

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 개수변경
     * @group PresetProduct(연관상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function updateCount(PresetProductRequest $request, $uuid)
    {
        $presetProduct = PresetProduct::where('uuid', $uuid)->first();

        if(!$presetProduct)
            return $this->respondNotFound();

        if($presetProduct->state != StatePresetProduct::BEFORE_PAYMENT)
            return $this->respondForbidden('결제준비 단계 이후로는 개수를 수정할 수 없습니다.');

        $presetProduct->update(['count' => $request->count]);


        return $this->respondSuccessfully(PresetProductMiniResource::make($presetProduct));
    }

    /** 구매확정
     * @group PresetProduct(연관상품)
     * @responseFile storage/responses/presetProduct.json
     */
    public function confirm(PresetProductRequest $request, PresetProduct $presetProduct)
    {
        if(!$presetProduct->can_confirm)
            return $this->respondForbidden();

        $presetProduct->update([
            'state' => StatePresetProduct::CONFIRMED
        ]);

        return $this->respondSuccessfully(PresetProductMiniResource::make($presetProduct));
    }

    /** 삭제
     * @group PresetProduct(연관상품)
     */
    public function destroy(PresetProductRequest $request, $uuid)
    {
        $presetProduct = PresetProduct::where('uuid', $uuid)->first();

        if(!$presetProduct)
            return $this->respondNotFound();

        if($presetProduct->state != StatePresetProduct::BEFORE_PAYMENT)
            return $this->respondForbidden('결제준비 단계 이후로는 삭제할 수 없습니다.');

        if(!$presetProduct->additional)
            return $this->respondForbidden('추가상품만 삭제할 수 있습니다.');

        $presetProduct->delete();

        return $this->respondSuccessfully();
    }
}
