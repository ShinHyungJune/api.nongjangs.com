<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\TypePointHistory;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PointHistoryResource;
use App\Http\Requests\PointHistoryRequest;
use App\Models\PointHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PointHistoryController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup PointHistory(포인트내역)
     * @priority 9
     * @responseFile storage/responses/pointHistories.json
     */
    public function index(PointHistoryRequest $request)
    {
        $items = PointHistory::whereHas('user', function($query) use($request){
            $query->where('name', 'LIKE', '%'.$request->word.'%')
                ->orWhere('contact', 'LIKE', '%'.$request->word.'%');
        });

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

        $items = $items->latest()->paginate(10);

        return PointHistoryResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup PointHistory(포인트내역)
     * @priority 9
     * @responseFile storage/responses/pointHistory.json
     */
    public function show(PointHistory $pointHistory)
    {
        return $this->respondSuccessfully(PointHistoryResource::make($pointHistory));
    }

    /** 생성
     * @group 관리자
     * @subgroup PointHistory(포인트내역)
     * @priority 9
     * @responseFile storage/responses/pointHistory.json
     */
    public function store(PointHistoryRequest $request)
    {
        $user = User::withTrashed()->find($request->user_id);

        $user->update(['point' => $user->point + $request->point]);

        $createdItem = PointHistory::create(array_merge($request->all(), [
            'type' => TypePointHistory::ADMIN_GIVE,
            'increase' => 1,
            'point_current' => $user->point,
        ]));

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(PointHistoryResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup PointHistory(포인트내역)
     * @priority 9
     * @responseFile storage/responses/pointHistory.json
     */
    public function update(PointHistoryRequest $request, PointHistory $pointHistory)
    {
        $pointHistory->update($request->all());

        if($request->files_remove_ids){
            $medias = $pointHistory->getMedia("img");

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
                $pointHistory->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(PointHistoryResource::make($pointHistory));
    }

    /** 삭제
     * @group 관리자
     * @subgroup PointHistory(포인트내역)
     * @priority 9
     */
    public function destroy(PointHistoryRequest $request)
    {
        PointHistory::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
