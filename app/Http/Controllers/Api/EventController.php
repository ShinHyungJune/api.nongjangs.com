<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends ApiController
{
    /**
     * @group Event(이벤트)
     * @responseFile storage/responses/events.json
     */
    public function index(EventRequest $request)
    {
        $items = new Event();

        $items = $items->orderBy('started_at', 'desc')->paginate(8);

        return EventResource::collection($items);
    }

    /**
     * @group Event(이벤트)
     * @responseFile storage/responses/event.json
     */
    public function show(Event $event)
    {
        $event->update(['count_view' => $event->count_view + 1]);

        return $this->respondSuccessfully(EventResource::make($event));
    }

}
