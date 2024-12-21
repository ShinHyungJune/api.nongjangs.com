<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * @group Banner(배너)
     * @priority 1
     * @responseFile storage/responses/banners.json
     */

    public function index(BannerRequest $request)
    {
        $items = new Banner();

        if($request->type)
            $items = $items->where('type', $request->type);

        $items = $items->orderBy('order', 'asc')->latest()->paginate(30);

        return BannerResource::collection($items);
    }
}
