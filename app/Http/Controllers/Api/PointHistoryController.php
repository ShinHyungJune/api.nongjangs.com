<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PointHistoryRequest;
use App\Http\Resources\PointHistoryResource;
use App\Models\PointHistory;
use Illuminate\Http\Request;

class PointHistoryController extends ApiController
{
    /**
     * @group PointHistory(포인트 내역)
     * @responseFile storage/responses/pointHistories.json
     */
    public function index(PointHistoryRequest $request)
    {
        $items = auth()->user()->pointHistories();

        if(isset($request->increase))
            $items = $items->where('increase', $request->increase);

        $items = $items->latest()->paginate(12);

        return PointHistoryResource::collection($items);
    }

}
