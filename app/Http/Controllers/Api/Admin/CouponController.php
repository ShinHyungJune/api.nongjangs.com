<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CouponController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Coupon(쿠폰)
     * @responseFile storage/responses/coupons.json
     */
    public function index(CouponRequest $request)
    {
        $items = new Coupon();

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

        $items = $items->latest()->paginate(10);

        return CouponResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Coupon(쿠폰)
     * @responseFile storage/responses/coupons.json
     */
    public function show(Coupon $coupon)
    {
        return $this->respondSuccessfully(CouponResource::make($coupon));
    }

    /** 생성
     * @group 관리자
     * @subgroup Coupon(쿠폰)
     * @priority 1
     * @responseFile storage/responses/coupons.json
     */
    public function store(CouponRequest $request)
    {
        $createdItem = Coupon::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(CouponResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Coupon(쿠폰)
     * @responseFile storage/responses/coupons.json
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->all());

        if($request->files_remove_ids){
            $medias = $coupon->getMedia("img");

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
                $coupon->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(CouponResource::make($coupon));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Coupon(쿠폰)
     */
    public function destroy(CouponRequest $request)
    {
        Coupon::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
