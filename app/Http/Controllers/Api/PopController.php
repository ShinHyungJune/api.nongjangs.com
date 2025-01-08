<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatePresetProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\PopRequest;
use App\Http\Requests\PresetRequest;
use App\Http\Resources\PopResource;
use App\Http\Resources\PresetResource;
use App\Models\Pop;
use App\Models\Preset;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PopController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Pop(팝업배너)
     * @responseFile storage/responses/pops.json
     */
    public function index(PopRequest $request)
    {
        $items = Pop::where('open', 1)
            ->where('started_at', '<=', Carbon::now()->startOfDay())
            ->where('finished_at', '>=', Carbon::now()->endOfDay());

        $items = $items->orderBy('order', 'asc')->latest()->paginate(30);

        return PopResource::collection($items);
    }
}
