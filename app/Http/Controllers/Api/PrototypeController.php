<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatePresetProduct;
use App\Enums\StatePrototype;
use App\Http\Controllers\Controller;
use App\Http\Requests\PrototypeRequest;
use App\Http\Resources\PrototypeResource;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Prototype;
use Illuminate\Http\Request;

class PrototypeController extends ApiController
{
    /** 목록
     * @group Prototype(시안)
     * @responseFile storage/responses/prototypes.json
     */
    public function index(PrototypeRequest $request)
    {
        $presetProduct = PresetProduct::where('uuid', $request->preset_product_uuid)->first();

        $items = $presetProduct->prototypes()->latest()->paginate(100);

        return PrototypeResource::collection($items);
    }

    /** 시안확정
     * @group Prototype(시안)
     * @responseFile storage/responses/prototype.json
     */
    public function confirm(PrototypeRequest $request, Prototype $prototype)
    {
        $presetProduct = PresetProduct::where('uuid', $request->preset_product_uuid)->first();

        if(!$presetProduct)
            return $this->respondForbidden('유효하지 않은 데이터입니다.');

        $prototype = $presetProduct->prototypes()->find($prototype->id);

        if(!$prototype)
            return $this->respondForbidden('권한이 없습니다. 주문정보와 함께 1:1문의로 문의를 남겨주세요.');

        if($presetProduct->prototypes()->where('confirmed', 1)->count() > 0)
            return $this->respondForbidden('이미 확정하셨습니다. 수정을 원하시면 고객센터로 문의주세요. 02-2274-6861');

        /*if($presetProduct->state != StatePresetProduct::FINISH_PROTOTYPE)
            return $this->respondForbidden('시안제작완료일때만 시안을 컨펌할 수 있습니다.');*/

        $prototype->update([
            'confirmed' => 1,
        ]);

        return $this->respondSuccessfully(PrototypeResource::make($prototype));
    }
}
