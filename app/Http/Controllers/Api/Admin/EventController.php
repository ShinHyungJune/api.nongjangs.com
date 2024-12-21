<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Event(이벤트)
     * @priority 4
     * @responseFile storage/responses/events.json
     */
    public function index(EventRequest $request)
    {
        $items = Event::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(10);

        return EventResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Event(이벤트)
     * @priority 4
     * @responseFile storage/responses/event.json
     */
    public function show(Event $event)
    {
        return $this->respondSuccessfully(EventResource::make($event));
    }

    /** 생성
     * @group 관리자
     * @subgroup Event(이벤트)
     * @priority 4
     * @responseFile storage/responses/event.json
     */
    public function store(EventRequest $request)
    {
        $createdItem = Event::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(EventResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Event(이벤트)
     * @priority 4
     * @responseFile storage/responses/event.json
     */
    public function update(EventRequest $request, Event $event)
    {
        $event->update($request->all());

        if($request->files_remove_ids){
            $medias = $event->getMedia("img");

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
                $event->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(EventResource::make($event));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Event(이벤트)
     * @priority 4
     */
    public function destroy(EventRequest $request)
    {
        Event::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
