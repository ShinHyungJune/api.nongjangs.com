<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatePresetProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\PopRequest;
use App\Http\Requests\PresetRequest;
use App\Http\Resources\PresetResource;
use App\Models\Preset;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PopController extends ApiController
{
    /**
     * @group 영어명(한글명)
     * @responseFile storage/responses/examples.json
     */
    public function store(PopRequest $request)
    {

    }
}
