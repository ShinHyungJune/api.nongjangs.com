<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\TypePointHistory;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PointHistoryResource;
use App\Http\Requests\PointHistoryRequest;
use App\Models\PointHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PointHistoryController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup PointHistory(포인트내역)
     * @responseFile storage/responses/pointHistories.json
     */
    public function index(PointHistoryRequest $request)
    {
        $items = new PointHistory();

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

        if($request->start_expired_at)
            $items = $items->whereHas('point', function ($query) use($request){
                $query->where('expired_at', '>=', Carbon::make($request->start_expired_at)->startOfDay());
            });

        if($request->finish_expired_at)
            $items = $items->whereHas('point', function ($query) use($request){
                $query->where('expired_at', '<=', Carbon::make($request->finish_expired_at)->endOfDay());
            });

        $items = $items->latest()->paginate(10);

        return PointHistoryResource::collection($items);
    }

    /** 생성
     * @group 관리자
     * @subgroup PointHistory(포인트내역)
     * @responseFile storage/responses/pointHistories.json
     */
    public function store(PointHistoryRequest $request)
    {
        $user = User::find($request->user_id);

        if($request->increase)
            $user->givePoint($request->point, TypePointHistory::ADMIN_GIVE, null, $request->memo);
        else
            $user->takePoint($request->point, TypePointHistory::ADMIN_TAKE, null, null, $request->memo);

        return $this->respondSuccessfully();
    }

}
