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
    public function index(CommentRequest $request)
    {
        $items = Comment::withCount('likes as count_like');

        $request['order_by'] = $request->order_by ?? 'count_like';

        if($request->commentable_type)
            $items = $items->where('commentable_type', $request->commentable_type);

        if($request->commentable_id)
            $items = $items->where('commentable_id', $request->commentable_id);

        $items = $items->orderBy($request->order_by, 'desc')->latest()->paginate(12);

        return CommentResource::collection($items);
    }

    /** 생성
     * @group 사용자
     * @subgroup Comment(댓글)
     * @responseFile storage/responses/comment.json
     */
    public function store(CommentRequest $request)
    {
        $comment = auth()->user()->comments()->create($request->validated());

        return $this->respondSuccessfully(CommentResource::make($comment));
    }
    
    /** 수정
     * @group 사용자
     * @subgroup Comment(댓글)
     * @responseFile storage/responses/comment.json
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        if($comment->user_id != auth()->id())
            return $this->respondForbidden();

        $comment->update($request->validated());

        return $this->respondSuccessfully(CommentResource::make($comment));
    }
    
    /** 삭제
     * @group 사용자
     * @subgroup Comment(댓글)
     */
    public function destroy(Comment $comment)
    {
        if($comment->user_id != auth()->id())
            return $this->respondForbidden();

        $comment->delete();

        return $this->respondSuccessfully();
    }
}
