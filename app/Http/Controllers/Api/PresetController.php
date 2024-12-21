<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatePresetProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\PresetRequest;
use App\Http\Resources\PresetResource;
use App\Models\Preset;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PresetController extends ApiController
{
    /**
     * @group Preset(상품조합)
     * @responseFile storage/responses/preset.json
     */
    public function store(PresetRequest $request)
    {
        if(!auth()->user() && !$request->guest_id)
            return $this->respondForbidden('비회원이라면 고유번호가 필요합니다. 관리자에게 문의하세요.');

        $preset = Preset::create([
            'user_id' => auth()->user() ? auth()->id() : null,
            'guest_id' => auth()->user() ? null : $request->guest_id
        ]);

        $preset->attachProducts($request);

        return $this->respondSuccessfully(PresetResource::make($preset));
    }
}
