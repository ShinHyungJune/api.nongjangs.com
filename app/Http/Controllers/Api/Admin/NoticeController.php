<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\NoticeResource;
use App\Http\Requests\NoticeRequest;
use App\Models\Notice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NoticeController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Notice(공지사항)
     * @priority 6
     * @responseFile storage/responses/notices.json
     */
    public function index(NoticeRequest $request)
    {
        $items = Notice::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(10);

        return NoticeResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Notice(공지사항)
     * @priority 6
     * @responseFile storage/responses/notice.json
     */
    public function show(Notice $notice)
    {
        return $this->respondSuccessfully(NoticeResource::make($notice));
    }

    /** 생성
     * @group 관리자
     * @subgroup Notice(공지사항)
     * @priority 6
     * @responseFile storage/responses/notice.json
     */
    public function store(NoticeRequest $request)
    {
        $createdItem = Notice::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(NoticeResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Notice(공지사항)
     * @priority 6
     * @responseFile storage/responses/notice.json
     */
    public function update(NoticeRequest $request, Notice $notice)
    {
        $notice->update($request->all());

        if($request->files_remove_ids){
            $medias = $notice->getMedia("img");

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
                $notice->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(NoticeResource::make($notice));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Notice(공지사항)
     * @priority 6
     */
    public function destroy(NoticeRequest $request)
    {
        Notice::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
