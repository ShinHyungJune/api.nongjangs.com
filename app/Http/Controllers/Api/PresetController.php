<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PresetRequest;
use App\Http\Resources\PresetResource;
use App\Models\Option;
use App\Models\Preset;

class PresetController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Preset(상품조합)
     * @responseFile storage/responses/preset.json
     */
    public function store(PresetRequest $request)
    {
        $preset = auth()->user()->presets()->create();

        $result = $preset->attachProducts($request);

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        return $this->respondSuccessfully(PresetResource::make($preset));
    }

    /** 상세
     * @group 사용자
     * @subgroup Preset(상품조합)
     * @responseFile storage/responses/preset.json
     */
    public function update(PresetRequest $request, Preset $preset)
    {
        if(!$preset->can_order)
            return $this->respondForbidden('수정할 수 없습니다.');

        $result = $preset->attachProducts($request);

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        return $this->respondSuccessfully(PresetResource::make($preset));
    }
}
