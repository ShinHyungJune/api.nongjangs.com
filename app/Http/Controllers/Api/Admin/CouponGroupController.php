<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponGroupResource;
use App\Http\Requests\CouponGroupRequest;
use App\Models\CouponGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CouponGroupController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup CouponGroup(쿠폰)
     * @priority 12
     * @responseFile storage/responses/couponGroups.json
     */
    public function index(CouponGroupRequest $request)
    {
        $items = CouponGroup::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(30);

        return CouponGroupResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup CouponGroup(쿠폰)
     * @priority 12
     * @responseFile storage/responses/couponGroup.json
     */
    public function show(CouponGroup $couponGroup)
    {
        return $this->respondSuccessfully(CouponGroupResource::make($couponGroup));
    }

    /** 생성
     * @group 관리자
     * @subgroup CouponGroup(쿠폰)
     * @priority 12
     * @responseFile storage/responses/couponGroup.json
     */
    public function store(CouponGroupRequest $request)
    {
        $createdItem = CouponGroup::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(CouponGroupResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup CouponGroup(쿠폰)
     * @priority 12
     * @responseFile storage/responses/couponGroup.json
     */
    public function update(CouponGroupRequest $request, CouponGroup $couponGroup)
    {
        $couponGroup->update($request->all());

        if($request->files_remove_ids){
            $medias = $couponGroup->getMedia("img");

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
                $couponGroup->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(CouponGroupResource::make($couponGroup));
    }

    /** 삭제
     * @group 관리자
     * @subgroup CouponGroup(쿠폰)
     * @priority 12
     */
    public function destroy(CouponGroupRequest $request)
    {
        CouponGroup::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
