<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Pop */
class PopResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'img' => $this->img ?? '',

            'title' => $this->title,
            'url' => $this->url,
            'open' => $this->open,
            'started_at' => $this->started_at ? Carbon::make($this->started_at)->format('Y-m-d') : '',
            'finished_at' => $this->finished_at ? Carbon::make($this->finished_at)->format('Y-m-d') : '',
        ];
    }
}
