<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Like */
class LikeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'likeable_id' => $this->likeable_id,
            'likeable_type' => $this->likeable_type,
            'created_at' => $this->created_at,

        ];
    }
}
