<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\RefundResource;
use App\Http\Requests\RefundRequest;
use App\Models\Refund;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RefundController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Refund(교환요청)
     * @priority 19
     * @responseFile storage/responses/refunds.json
     */
    public function index(RefundRequest $request)
    {
        $items = Refund::whereHas('user', function($query) use($request){
            $query->where("name", "LIKE", "%".$request->word."%")
                ->orWhere("contact", "LIKE", "%".$request->word."%");
        });

        if($request->state)
            $items = $items->where('state', $request->state);

        $items = $items->latest()->paginate(10);

        return RefundResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Refund(교환요청)
     * @priority 19
     * @responseFile storage/responses/refund.json
     */
    public function show(Refund $refund)
    {
        return $this->respondSuccessfully(RefundResource::make($refund));
    }

    /** 생성
     * @group 관리자
     * @subgroup Refund(교환요청)
     * @priority 19
     * @responseFile storage/responses/refund.json
     */
    public function store(RefundRequest $request)
    {
        $createdItem = Refund::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(RefundResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Refund(교환요청)
     * @priority 19
     * @responseFile storage/responses/refund.json
     */
    public function update(RefundRequest $request, Refund $refund)
    {
        $refund->update($request->all());

        if($request->files_remove_ids){
            $medias = $refund->getMedia("img");

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
                $refund->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(RefundResource::make($refund));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Refund(교환요청)
     * @priority 19
     */
    public function destroy(RefundRequest $request)
    {
        Refund::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
