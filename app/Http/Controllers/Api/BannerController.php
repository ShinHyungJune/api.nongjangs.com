<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BannerController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Banner(배너)
     * @responseFile storage/responses/banners.json
     */
    public function index(BannerRequest $request)
    {
        $items = Banner::where('started_at', '<=', Carbon::now()->startOfDay())
            ->where('finished_at', '>=', Carbon::now()->endOfDay());

        if($request->type)
            $items = $items->where('type', $request->type);

        $items = $items->orderBy('order', 'asc')->latest()->paginate(30);

        return BannerResource::collection($items);
    }
}
