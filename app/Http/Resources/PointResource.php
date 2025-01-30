<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Point */
class PointResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'point' => $this->point,
            'expired_at' => $this->expired_at,
            'format_expired_at' => $this->expired_at ? Carbon::make($this->expired_at)->format('Y.m.d H:i') : '',
            'user_id' => $this->user_id,
        ];
    }
}
