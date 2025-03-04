<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Tag(태그)
     * @responseFile storage/responses/tags.json
     */
    public function index(TagRequest $request)
    {
        $items = Tag::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        if($request->type)
            $items = $items->where('type', $request->type);

        $items = $items->latest()->paginate(25);

        return TagResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Tag(태그)
     * @responseFile storage/responses/tag.json
     */
    public function show(Tag $tag)
    {
        return $this->respondSuccessfully(TagResource::make($tag));
    }

    /** 생성
     * @group 관리자
     * @subgroup Tag(태그)
     * @responseFile storage/responses/tag.json
     */
    public function store(TagRequest $request)
    {
        $createdItem = Tag::create($request->validated());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(TagResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Tag(태그)
     * @responseFile storage/responses/tag.json
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());

        if($request->files_remove_ids){
            $medias = $tag->getMedia("img");

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
                $tag->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(TagResource::make($tag));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Tag(태그)
     */
    public function destroy(TagRequest $request)
    {
        Tag::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }

    /** 선택 노출
     * @group 관리자
     * @subgroup Tag(태그)
     * @responseFile storage/responses/tags.json
     */
    public function open(TagRequest $request)
    {
        Tag::whereIn('id', $request->ids)->update(['open' => 1]);

        return $this->respondSuccessfully();
    }

    /** 순서변경(up)
     * @group 관리자
     * @subgroup Tag(태그)
     */
    public function up(Request $request, Tag $tag)
    {
        $prevOrder = $tag->order;

        $target = Tag::where('type', $request->type)->orderBy('order', 'desc')->where('id', '!=', $tag->id)->where('order', '<=', $tag->order)->first();

        if($target) {
            $changeOrder = $target->order == $tag->order ? $tag->order - 1 : $target->order;
            $tag->update(["order" => $changeOrder]);
            $target->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();
    }

    /** 순서변경(down)
     * @group 관리자
     * @subgroup Tag(태그)
     */
    public function down(Request $request, Tag $tag)
    {
        $prevOrder = $tag->order;

        $target = Tag::where('type', $request->type)->orderBy("order", "asc")->where("id", "!=", $tag->id)->where("order", ">=", $tag->order)->first();

        if($target) {
            $changeOrder = $target->order == $tag->order ? $tag->order + 1 : $target->order;
            $tag->update(["order" => $changeOrder]);
            $target->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();
    }
}
