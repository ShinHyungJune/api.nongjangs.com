<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BookmarkRequest;

class BookmarkController extends ApiController
{
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
