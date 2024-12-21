<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponHistoryResource;
use App\Http\Requests\CouponHistoryRequest;
use App\Models\CouponHistory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CouponHistoryController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup CouponHistory(쿠폰내역)
     * @priority 8
     * @responseFile storage/responses/couponHistories.json
     */
    public function index(CouponHistoryRequest $request)
    {
        $items = CouponHistory::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(10);

        return CouponHistoryResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup CouponHistory(쿠폰내역)
     * @priority 8
     * @responseFile storage/responses/couponHistory.json
     */
    public function show(CouponHistory $couponHistory)
    {
        return $this->respondSuccessfully(CouponHistoryResource::make($couponHistory));
    }

    /** 생성
     * @group 관리자
     * @subgroup CouponHistory(쿠폰내역)
     * @priority 8
     * @responseFile storage/responses/couponHistory.json
     */
    public function store(CouponHistoryRequest $request)
    {
        $createdItem = CouponHistory::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(CouponHistoryResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup CouponHistory(쿠폰내역)
     * @priority 8
     * @responseFile storage/responses/couponHistory.json
     */
    public function update(CouponHistoryRequest $request, CouponHistory $couponHistory)
    {
        $couponHistory->update($request->all());

        if($request->files_remove_ids){
            $medias = $couponHistory->getMedia("img");

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
                $couponHistory->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(CouponHistoryResource::make($couponHistory));
    }

    /** 삭제
     * @group 관리자
     * @subgroup CouponHistory(쿠폰내역)
     * @priority 8
     */
    public function destroy(CouponHistoryRequest $request)
    {
        CouponHistory::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
