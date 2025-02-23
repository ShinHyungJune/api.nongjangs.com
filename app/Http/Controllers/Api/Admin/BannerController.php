<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BannerController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Banner(배너)
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

    /** 상세
     * @group 관리자
     * @subgroup Banner(배너)
     * @responseFile storage/responses/banner.json
     */
    public function show(Banner $banner)
    {
        return $this->respondSuccessfully(BannerResource::make($banner));
    }

    /** 생성
     * @group 관리자
     * @subgroup Banner(배너)
     * @responseFile storage/responses/banner.json
     */
    public function store(BannerRequest $request)
    {
        $createdItem = Banner::create($request->all());

        if(is_array($request->file("pc"))){
            foreach($request->file("pc") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("pc", "s3");
            }
        }

        if(is_array($request->file("mobile"))){
            foreach($request->file("mobile") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("mobile", "s3");
            }
        }

        return $this->respondSuccessfully(BannerResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Banner(배너)
     * @responseFile storage/responses/banner.json
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        $banner->update($request->all());

        if($request->pc_remove_ids){
            $medias = $banner->getMedia("pc");

            foreach($medias as $media){
                foreach($request->pc_remove_ids as $id){
                    if((int) $media->id == (int) $id){
                        $media->delete();
                    }
                }
            }
        }

        if($request->mobile_remove_ids){
            $medias = $banner->getMedia("mobile");

            foreach($medias as $media){
                foreach($request->mobile_remove_ids as $id){
                    if((int) $media->id == (int) $id){
                        $media->delete();
                    }
                }
            }
        }

        if(is_array($request->file("pc"))){
            foreach($request->file("pc") as $file){
                $banner->addMedia($file["file"])->toMediaCollection("pc", "s3");
            }
        }

        if(is_array($request->file("mobile"))){
            foreach($request->file("mobile") as $file){
                $banner->addMedia($file["file"])->toMediaCollection("mobile", "s3");
            }
        }

        return $this->respondSuccessfully(BannerResource::make($banner));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Banner(배너)
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();

        return $this->respondSuccessfully();
    }

    /** 앞순서로 변경
     * @group 관리자
     * @subgroup Banner(팝업)
     */
    public function up(Banner $banner, Request $request)
    {
        $prevOrder = $banner->order;

        $target = Banner::orderBy('order', 'desc')->where('id', '!=', $banner->id)->where('order', '<=', $banner->order)->first();

        if($target) {
            $changeOrder = $target->order == $banner->order ? $banner->order - 1 : $target->order;
            $banner->update(["order" => $changeOrder]);
            $target->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();
    }

    /** 뒷순서로 변경
     * @group 관리자
     * @subgroup Banner(팝업)
     */
    public function down(Banner $banner, Request $request)
    {
        $prevOrder = $banner->order;

        $target = Banner::orderBy("order", "asc")->where("id", "!=", $banner->id)->where("order", ">=", $banner->order)->first();

        if($target) {
            $changeOrder = $target->order == $banner->order ? $banner->order + 1 : $target->order;
            $banner->update(["order" => $changeOrder]);
            $target->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();
    }
}
