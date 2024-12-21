<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\TypeAlarm;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PrototypeResource;
use App\Http\Requests\PrototypeRequest;
use App\Models\Alarm;
use App\Models\Prototype;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PrototypeController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Prototype(시안)
     * @priority 14
     * @responseFile storage/responses/prototypes.json
     */
    public function index(PrototypeRequest $request)
    {
        $items = Prototype::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        if($request->preset_product_id)
            $items = $items->where('preset_product_id', $request->preset_product_id);

        $items = $items->latest()->paginate(10);

        return PrototypeResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Prototype(시안)
     * @priority 14
     * @responseFile storage/responses/prototype.json
     */
    public function show(Prototype $prototype)
    {
        return $this->respondSuccessfully(PrototypeResource::make($prototype));
    }

    /** 생성
     * @group 관리자
     * @subgroup Prototype(시안)
     * @priority 14
     * @responseFile storage/responses/prototype.json
     */
    public function store(PrototypeRequest $request)
    {
        $createdItem = Prototype::create($request->all());

        if($request->comment)
            $createdItem->comments()->create([
                'preset_product_id' => $request->preset_product_id,
                'description' => $request->comment,
            ]);

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        return $this->respondSuccessfully(PrototypeResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Prototype(시안)
     * @priority 14
     * @responseFile storage/responses/prototype.json
     */
    public function update(PrototypeRequest $request, Prototype $prototype)
    {
        $prototype->update($request->all());

        if($request->files_remove_ids){
            $medias = $prototype->getMedia("imgs");

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
                $prototype->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        Alarm::create([
            'contact' => $prototype->presetProduct->preset->order->buyer_contact,
            'prototype_id' => $prototype->id,
            'type' => TypeAlarm::PROTOTYPE_CREATED,
        ]);

        $prototype->presetProduct->update(['alert_send_check_prototype_message_at' => Carbon::now()]);

        return $this->respondSuccessfully(PrototypeResource::make($prototype));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Prototype(시안)
     * @priority 14
     */
    public function destroy(PrototypeRequest $request)
    {
        Prototype::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
