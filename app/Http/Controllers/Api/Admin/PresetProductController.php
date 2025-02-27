<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\StateOrder;
use App\Enums\StatePresetProduct;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PresetProductResource;
use App\Http\Requests\PresetProductRequest;
use App\Models\Order;
use App\Models\Package;
use App\Models\PresetProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PresetProductController extends ApiController
{
    /** 통계
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @responseFile storage/responses/presetProductsCounts.json
     */
    public function counts(PresetProductRequest $request)
    {
        $currentPackage = Package::getCanOrder();

        $result = [
            'currentPackage' => PackageResource::make($currentPackage),
            'count_current_preset_product' => $currentPackage ? PresetProduct::whereNotIn('state', [StatePresetProduct::BEFORE_PAYMENT, StatePresetProduct::CANCEL])->where('package_id', $currentPackage->id)->count() : 0,

        ];

        return $this->respondSuccessfully($result);
    }


    /** 목록
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @responseFile storage/responses/presetProducts.json
     */
    public function index(PresetProductRequest $request)
    {
        $items = PresetProduct::where(function($query) use($request){
            $query->where("product_title", "LIKE", "%".$request->word."%")
                ->orWhereHas("preset", function ($query) use($request){
                   $query->whereHas('order', function ($query) use ($request){
                       $query->where("payment_id","LIKE", "%".$request->word."%")
                           ->orWhere("user_name", "LIKE", "%".$request->word."%")
                           ->orWhere("user_email", "LIKE", "%".$request->word."%")
                           ->orWhere("user_contact", "LIKE", "%".$request->word."%");
                   });
                })->orWhere('delivery_number', "LIKE", "%".$request->word."%");
        });

        if($request->user_id)
            $items = $items->whereHas('preset', function ($query) use($request){
                $query->where('user_id', $request->user_id);
            });

        if($request->state)
            $items = $items->where('state', $request->state);

        if($request->states)
            $items = $items->whereIn('state', $request->states);

        if($request->has_column)
            $items = $items->whereNotNull($request->has_column);

        //
        if($request->type_package)
            $items = $items->where('package_type', $request->type_package);

        if($request->package_id)
            $items = $items->where('package_id', $request->package_id);

        if($request->started_at)
            $items = $items->where('created_at', '>=', Carbon::make($request->started_at)->startOfDay());

        if($request->finished_at)
            $items = $items->where('created_at', '<=', Carbon::make($request->finished_at)->endOfDay());

        $request['order_by'] = $request->order_by ?? 'created_at';
        $request['align'] = $request->align ?? 'desc';

        $items = $items->orderBy($request->order_by, $request->align)->paginate(25);

        return PresetProductResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @responseFile storage/responses/presetProduct.json
     */
    public function show(PresetProduct $presetProduct)
    {
        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 생성
     * @group 관리자
     * @subgroup PresetProduct(출고)
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
     * @responseFile storage/responses/presetProduct.json
     */
    public function update(PresetProductRequest $request, PresetProduct $presetProduct)
    {
        $presetProduct->update($request->all());

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
     */
    public function destroy(PresetProductRequest $request)
    {
        PresetProduct::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
