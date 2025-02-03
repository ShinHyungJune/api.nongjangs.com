<?php

namespace App\Http\Resources;

use App\Models\VegetableStory;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Comment */
class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        if($this->commentable_type == VegetableStory::class)
            $commentable = VegetableStoryResource::make($this->commentable);

        return [
            'id' => $this->id,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'nickname' => $this->user->nickname
            ] : [
                'id' => $this->user_id,
                'nickname' => "알 수 없는 사용자"
            ],
            'description' => $this->description,
            'commentable' => $commentable ?? "",

            'count_like' => $this->count_like,
            'is_like' => $this->is_like,
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d'),
        ];
    }
}
