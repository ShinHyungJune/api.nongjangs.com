<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BookmarkRequest;
use App\Http\Resources\BookmarkResource;

class BookmarkController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Bookmark(북마크)
     * @responseFile storage/responses/bookmarks.json
     */
    public function index(BookmarkRequest $request)
    {
        $items = auth()->user()->bookmarks();

        if($request->bookmarkable_type)
            $items = $items->where('bookmarkable_type', $request->bookmarkable_type);

        $items = $items->latest()->paginate(8);

        return BookmarkResource::collection($items);
    }

    /** 생성 또는 삭제 (토글)
     * @group 사용자
     * @subgroup Bookmark(북마크)
     */
    public function store(BookmarkRequest $request)
    {
        $bookmark = auth()->user()->bookmarks()
            ->where('bookmarkable_id', $request->bookmarkable_id)
            ->where('bookmarkable_type', $request->bookmarkable_type)
            ->first();

        $bookmark
            ? $bookmark->delete()
            : auth()->user()->bookmarks()->create($request->all());

        return $this->respondSuccessfully();
    }
}
