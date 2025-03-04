<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Faq(자주묻는질문)
     * @priority 5
     * @responseFile storage/responses/faqs.json
     */
    public function index(FaqRequest $request)
    {
        $items = Faq::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        if($request->faq_category_id)
            $items = $items->where('faq_category_id', $request->faq_category_id);

        $items = $items->latest()->paginate(10);

        return FaqResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Faq(자주묻는질문)
     * @priority 5
     * @responseFile storage/responses/faqs.json
     */
    public function show(Faq $faq)
    {
        return $this->respondSuccessfully(FaqResource::make($faq));
    }

    /** 생성
     * @group 관리자
     * @subgroup Faq(자주묻는질문)
     * @priority 5
     * @responseFile storage/responses/faqs.json
     */
    public function store(FaqRequest $request)
    {
        $createdItem = Faq::create(array_merge($request->validated(), [
            'user_id' => auth()->id(),
        ]));

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(FaqResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Faq(자주묻는질문)
     * @priority 5
     * @responseFile storage/responses/faqs.json
     */
    public function update(FaqRequest $request, Faq $faq)
    {
        $faq->update(array_merge($request->validated(), [
            'user_id' => auth()->id(),
        ]));

        if($request->files_remove_ids){
            $medias = $faq->getMedia("img");

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
                $faq->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(FaqResource::make($faq));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Faq(자주묻는질문)
     * @priority 5
     */
    public function destroy(FaqRequest $request)
    {
        Faq::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
