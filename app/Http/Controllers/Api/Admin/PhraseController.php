<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PhraseResource;
use App\Http\Requests\PhraseRequest;
use App\Models\Phrase;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PhraseController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup 문구
     * @responseFile storage/responses/phrases.json
     */
    public function index(PhraseRequest $request)
    {
        $items = Phrase::where(function($query) use($request){
            $query->where("description", "LIKE", "%".$request->word."%");
        });

        if($request->phrase_product_category_id)
            $items = $items->where('phrase_product_category_id', $request->phrase_product_category_id);

        if($request->phrase_receiver_category_id)
            $items = $items->where('phrase_receiver_category_id', $request->phrase_receiver_category_id);

        $items = $items->latest()->paginate(10);

        return PhraseResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup 문구
     * @responseFile storage/responses/phrase.json
     */
    public function show(Phrase $phrase)
    {
        return $this->respondSuccessfully(PhraseResource::make($phrase));
    }

    /** 생성
     * @group 관리자
     * @subgroup 문구
     * @responseFile storage/responses/phrase.json
     */
    public function store(PhraseRequest $request)
    {
        $createdItem = Phrase::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(PhraseResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup 문구
     * @responseFile storage/responses/phrase.json
     */
    public function update(PhraseRequest $request, Phrase $phrase)
    {
        $phrase->update($request->all());

        if($request->files_remove_ids){
            $medias = $phrase->getMedia("img");

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
                $phrase->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(PhraseResource::make($phrase));
    }

    /** 삭제
     * @group 관리자
     * @subgroup 문구
     */
    public function destroy(PhraseRequest $request)
    {
        Phrase::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
