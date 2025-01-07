<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Bookmark */
class BookmarkResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'bookmarkable_id' => $this->bookmarkable_id,
            'bookmarkable_type' => $this->bookmarkable_type,

            'user_id' => $this->user_id,
        ];
    }
}
