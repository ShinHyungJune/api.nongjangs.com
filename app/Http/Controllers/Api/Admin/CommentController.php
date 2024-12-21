<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommentController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Comment(코멘트)
     * @priority 15
     * @responseFile storage/responses/comments.json
     */
    public function index(CommentRequest $request)
    {
        $items = Comment::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(10);

        return CommentResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Comment(코멘트)
     * @priority 15
     * @responseFile storage/responses/comment.json
     */
    public function show(Comment $comment)
    {
        return $this->respondSuccessfully(CommentResource::make($comment));
    }

    /** 생성
     * @group 관리자
     * @subgroup Comment(코멘트)
     * @priority 15
     * @responseFile storage/responses/comment.json
     */
    public function store(CommentRequest $request)
    {
        $createdItem = Comment::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(CommentResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Comment(코멘트)
     * @priority 155
     * @responseFile storage/responses/comment.json
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        $comment->update($request->all());

        if($request->files_remove_ids){
            $medias = $comment->getMedia("img");

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
                $comment->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(CommentResource::make($comment));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Comment(코멘트)
     * @priority 15
     */
    public function destroy(CommentRequest $request)
    {
        Comment::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
