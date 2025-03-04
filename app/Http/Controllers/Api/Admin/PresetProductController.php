<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\StateOrder;
use App\Enums\StatePresetProduct;
use App\Exports\MaterialsExport;
use App\Exports\PresetProductsExport;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PresetProductResource;
use App\Http\Requests\PresetProductRequest;
use App\Imports\PresetProductsImport;
use App\Models\Download;
use App\Models\Order;
use App\Models\Package;
use App\Models\PresetProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PresetProductController extends ApiController
{
    public function filter(PresetProductRequest $request)
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

        if($request->ids)
            $items = $items->whereIn('id', $request->ids);

        return $items;
    }

    public function filterMaterials(PresetProductRequest $request)
    {
        $package = Package::find($request->package_id);

        $presetProducts = $package->presetProducts()
            ->whereNotIn('state', [StatePresetProduct::BEFORE_PAYMENT,StatePresetProduct::CANCEL])
            ->get();

        $items = [];

        foreach($presetProducts as $presetProduct){
            $materials = $presetProduct->materials;

            foreach($materials as $material){
                $index = collect($items)->search(function($item) use ($material){
                    return $item['id'] == $material->id;
                });

                if($index !== false){
                    $items[$index]['count'] += $material->pivot->count;
                }else{
                    $items[] = [
                        'id' => $material->id,
                        'title' => $material->title,
                        'type' => $material->pivot->type,
                        'unit' => $material->pivot->unit,
                        'count' => $material->pivot->count,
                        'value' => $material->pivot->value,
                    ];
                }
            }
        }

        return $items;
    }

    /** 품목 목록(출고해야할 품목 목록 조회용)
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @responseFile storage/responses/presetProductsMaterials.json
     */
    public function materials(PresetProductRequest $request)
    {
        $items = $this->filterMaterials($request);

        return $this->respondSuccessfully($items);
    }

    /** 품목 목록 엑셀 다운로드(출고해야할 품목 목록 조회용)
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @responseFile storage/responses/presetProductsMaterials.json
     */
    public function exportMaterials(PresetProductRequest $request)
    {
        $download = Download::create();

        $path = $download->id."/"."품목.xlsx";

        $items = $this->filterMaterials($request);

        Excel::store(new MaterialsExport($items), $path, "s3");

        $url = Storage::disk("s3")->url($path);

        return $this->respondSuccessfully($url);
    }

    /** 통계
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @responseFile storage/responses/presetProductsCounts.json
     */
    public function counts(PresetProductRequest $request)
    {
        $currentPackage = Package::getCanOrder();

        $result = [
            'currentPackage' => $currentPackage ? PackageResource::make($currentPackage) : '',
            'count_current_preset_product' => $currentPackage ? PresetProduct::whereNotIn('state', [StatePresetProduct::BEFORE_PAYMENT, StatePresetProduct::CANCEL])->where('package_id', $currentPackage->id)->count() : 0,

            'count_ready' => PresetProduct::where('state', StatePresetProduct::READY)->count(),
            'count_will_out' => PresetProduct::where('state', StatePresetProduct::WILL_OUT)->count(),
            'count_ongoing_delivery' => PresetProduct::where('state', StatePresetProduct::ONGOING_DELIVERY)->count(),
            'count_wait' => PresetProduct::where('state', StatePresetProduct::WAIT)->count(),
            'count_delivered' => PresetProduct::whereIn('state', [StatePresetProduct::DELIVERED, StatePresetProduct::CONFIRMED])->count(),

            'count_request_cancel' => PresetProduct::where('state', StatePresetProduct::REQUEST_CANCEL)->count(),
            'count_cancel' => PresetProduct::where('state', StatePresetProduct::CANCEL)->count(),
            'sum_price_cancel_day' => PresetProduct::whereDate('cancel_at', Carbon::now()->format('Y-m-d'))->where('state', StatePresetProduct::CANCEL)->sum('price'),
            'sum_price_cancel_month' => PresetProduct::whereMonth('cancel_at', Carbon::now()->month)->where('state', StatePresetProduct::CANCEL)->sum('price'),
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
        $items = $this->filter($request);

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

    /** 배송지 수정
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @responseFile storage/responses/presetProducts.json
     */
    public function updateDeliveryAddress(PresetProductRequest $request, PresetProduct $presetProduct)
    {
        $presetProduct->update($request->validated());

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }

    /** 출고예정처리
     * @group 관리자
     * @subgroup PresetProduct(출고)
     */
    public function willOut(PresetProductRequest $request)
    {
        PresetProduct::where('state', StatePresetProduct::READY)->whereIn('id', $request->ids)->update([
            'state' => StatePresetProduct::WILL_OUT
        ]);

        return $this->respondSuccessfully();
    }

    /** 상태 변경
     * @group 관리자
     * @subgroup PresetProduct(출고)
     */
    public function updateState(PresetProduct $presetProduct, PresetProductRequest $request)
    {
        if($request->state == StatePresetProduct::DENY_CANCEL)
            $request->validate([
                'reason_deny_cancel' => 'required|string|max:500'
            ]);

        $presetProduct->update($request->validated());

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

    /** 엑셀다운
     * @group 관리자
     * @subgroup PresetProduct(출고)
     */
    public function export(PresetProductRequest $request)
    {
        $max = 30000;

        $download = Download::create();

        $path = $download->id."/"."출고.xlsx";

        $items = $this->filter($request);

        if($items->count() > $max)
            return $this->respondForbidden("{$max}개 이하로 개수를 조정해주세요.");

        $items = $items->get();

        Excel::store(new PresetProductsExport($items), $path, "s3");

        $url = Storage::disk("s3")->url($path);

        return $this->respondSuccessfully($url);
    }

    /** 송장등록
     * @group 관리자
     * @subgroup PresetProduct(출고)
     */
    public function import(PresetProductRequest $request)
    {
        Excel::import(new PresetProductsImport, $request->file('file'));

        return $this->respondSuccessfully();
    }

    /** 취소
     * @group 관리자
     * @subgroup PresetProduct(출고)
     * @responseFile storage/responses/presetProduct.json
     */
    public function cancel(PresetProduct $presetProduct)
    {
        if(!$presetProduct->admin_can_cancel)
            return $this->respondForbidden('취소 불가능한 상태입니다.');

        $result = $presetProduct->cancel();

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        return $this->respondSuccessfully(PresetProductResource::make($presetProduct));
    }
}
