<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;

class CommentController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Comment(댓글)
     * @responseFile storage/responses/comments.json
     */
    public function index()
    {
        return CommentResource::collection(Comment::all());
    }

    /** 생성
     * @group 사용자
     * @subgroup Comment(댓글)
     * @responseFile storage/responses/comment.json
     */
    public function store(CommentRequest $request)
    {
        return new CommentResource(Comment::create($request->validated()));
    }
    
    /** 수정
     * @group 사용자
     * @subgroup Comment(댓글)
     * @responseFile storage/responses/comment.json
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        return new CommentResource($comment);
    }
    
    /** 삭제
     * @group 사용자
     * @subgroup Comment(댓글)
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json();
    }
}
