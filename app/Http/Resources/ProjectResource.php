<?php

namespace App\Http\Resources;

use App\Enums\StateProject;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Project */
class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'started_at' => $this->started_at,
            'format_started_at' => $this->started_at ? Carbon::make($this->started_at)->format('Y.m.d H:i') : '',
            'finished_at' => $this->finished_at,
            'format_finished_at' => $this->finished_at ? Carbon::make($this->finished_at)->format('Y.m.d H:i') : '',
            'count_goal' => $this->count_goal,
            'count_participate' => $this->count_participate,
            'product_id' => $this->product_id,

            'tags' => TagResource::collection($this->tags),
            'product' => $this->product ? ProductResource::make($this->product) : '',
            'img' => $this->img ?? '',
            'state' => $this->state,
            'format_state' => StateProject::getLabel($this->state),
            'ratio_progress' => $this->ratio_progress,
            'days_remain' => $this->days_remain,
            'time_remain' => $this->time_remain,
        ];
    }
}
