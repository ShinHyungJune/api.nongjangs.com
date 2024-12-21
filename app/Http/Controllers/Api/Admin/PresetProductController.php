<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\StatePresetProduct;
use App\Enums\StatePrototype;
use App\Enums\TypeAlarm;
use App\Enums\TypeDelivery;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PresetProductResource;
use App\Http\Requests\PresetProductRequest;
use App\Models\Alarm;
use App\Models\PresetProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PresetProductController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @priority 18
     * @responseFile storage/responses/presetProducts.json
     */
    public function index(PresetProductRequest $request)
    {
        $items = PresetProduct::where('state', '!=', StatePresetProduct::BEFORE_PAYMENT);

        if($request->state)
            $items = $items->where('state', $request->state);

        if($request->state_prototype){
            if($request->state_prototype == StatePrototype::EMPTY)
                $items = $items->where('title', null);

            if($request->state_prototype == StatePrototype::WAIT)
                $items = $items->where('title', '!=', null)->where('will_prototype_finished_at', null)->whereDoesntHave('prototypes');

            if($request->state_prototype == StatePrototype::ONGOING)
                $items = $items->where('will_prototype_finished_at', '!=', null)->whereDoesntHave('prototypes');

            if($request->state_prototype == StatePrototype::FINISH)
                $items = $items->whereHas('prototypes')->whereDoesntHave('prototypes', function ($query){
                    $query->where('confirmed', 1);
                });

            if($request->state_prototype == StatePrototype::CONFIRM)
                $items = $items->whereHas('prototypes', function ($query){
                    $query->where('confirmed', 1);
                });
        }

        if($request->order_id)
            $items = $items->whereHas('preset', function ($query) use($request){
                $query->where('order_id', $request->order_id);
            });

        $items = $items->latest()->paginate(10);

        return PresetProductResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @priority 18
     * @responseFile storage/responses/presetProduct.json
     */
    public function show(PresetProduct $presetProduct)
    {
        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 생성
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @priority 18
     * @responseFile storage/responses/presetProduct.json
     */
    public function store(PresetProductRequest $request)
    {
        $createdItem = PresetProduct::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(PresetProductResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @priority 18
     * @responseFile storage/responses/presetProduct.json
     */
    public function update(PresetProductRequest $request, PresetProduct $presetProduct)
    {
        $presetProduct->update($request->validated());

        if($request->files_remove_ids){
            $medias = $presetProduct->getMedia("img");

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
                $presetProduct->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 삭제
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @priority 18
     */
    public function destroy(PresetProductRequest $request)
    {
        PresetProduct::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }

    public function updateWillPrototypeFinishedAt(PresetProduct $presetProduct, PresetProductRequest $request)
    {
        $presetProduct->update(['will_prototype_finished_at' => $request->will_finished_at, 'state' => StatePresetProduct::ONGOING_PROTOTYPE]);

        $order = $presetProduct->preset->order;

        // 주문단위 관리를 위한 묶음처리
        if($order)
            $order->presetProducts()->update(['will_prototype_finished_at' => $request->will_finished_at, 'state' => StatePresetProduct::ONGOING_PROTOTYPE]);

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    public function updateState(PresetProduct $presetProduct, PresetProductRequest $request)
    {
        $presetProduct->update(['state' => $request->state]);

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    public function updateNeedAlertDelivery(PresetProduct $presetProduct, PresetProductRequest $request)
    {
        $presetProduct->update(['need_alert_delivery' => 0]);

        Alarm::create([
            'contact' => $presetProduct->preset->order->buyer_contact,
            'preset_product_id' => $presetProduct->id,
            'type' => TypeAlarm::PRESET_PRODUCT_DIRECT_DELIVERY_REQUIRED,
        ]);

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }
}
