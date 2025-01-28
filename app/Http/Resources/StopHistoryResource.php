<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\StopHistory */
class StopHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reason' => $this->reason,
            'and_so_on' => $this->and_so_on,
            'format_created_at' => Carbon::make($this->created_at)->format("Y.m.d H:i"),
        ];
    }
}
