<?php

namespace App\Http\Resources;

use App\Enums\StateEvent;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Event */
class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'count_view' => $this->count_view,
            'coupon_group_id' => $this->coupon_group_id ?? '',
            'couponGroup' => $this->couponGroup ?? '',
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,

            'img' => $this->img ?? '',
            'state' => $this->state,
            'format_state' => StateEvent::getLabel($this->state),
            'format_started_at' => $this->started_at ? Carbon::make($this->started_at)->format('Y.m.d') : '',
            'format_finished_at' => $this->finished_at ? Carbon::make($this->finished_at)->format('Y.m.d') : '',
        ];
    }
}
