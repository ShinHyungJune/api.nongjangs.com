<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\FarmStory */
class FarmStoryResource extends JsonResource
{
    public function toArray($request)
    {
        $user = User::withTrashed()->find($this->user_id);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'count_view' => $this->count_view,
            'user_id' => $this->user_id,
            'farm_id' => $this->farm_id,

            'img' => $this->img ?? '',
            'farm' => FarmResource::make($this->farm),
            'tags' => TagResource::collection($this->tags),
            'user' => $user ? UserResource::make($user) : '',
            'count_like' => $this->likes()->count(),
            'is_like' => $this->is_like,
            'internal' => $this->internal,

            'created_at' => $this->created_at,
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d'),
        ];
    }
}
