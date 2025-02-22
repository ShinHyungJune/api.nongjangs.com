<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookmarkResource;
use App\Http\Requests\BookmarkRequest;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookmarkController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Bookmark(북마크)
     * @responseFile storage/responses/bookmarks.json
     */
    public function index(BookmarkRequest $request)
    {
        $items = new Bookmark();

        if($request->user_id)
            $items = $items->where('user_id', $request->user_id);

        if($request->bookmarkable_type)
            $items = $items->where('bookmarkable_type', $request->bookmarkable_type);

        $items = $items->latest()->paginate(25);

        return BookmarkResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Bookmark(북마크)
     * @responseFile storage/responses/bookmark.json
     */
    public function show(Bookmark $bookmark)
    {
        return $this->respondSuccessfully(BookmarkResource::make($bookmark));
    }

    /** 생성
     * @group 관리자
     * @subgroup Bookmark(북마크)
     * @responseFile storage/responses/bookmark.json
     */
    public function store(BookmarkRequest $request)
    {
        $createdItem = Bookmark::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(BookmarkResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Bookmark(북마크)
     * @responseFile storage/responses/bookmark.json
     */
    public function update(BookmarkRequest $request, Bookmark $bookmark)
    {
        $bookmark->update($request->all());

        if($request->files_remove_ids){
            $medias = $bookmark->getMedia("img");

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
                $bookmark->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(BookmarkResource::make($bookmark));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Bookmark(북마크)
     */
    public function destroy(BookmarkRequest $request)
    {
        Bookmark::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
