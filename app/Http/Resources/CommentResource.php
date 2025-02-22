<?php

namespace App\Http\Resources;

use App\Models\User;
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

        $user = User::withTrashed()->find($this->user_id);

        return [
            'id' => $this->id,
            'user' => $user ? [
                'id' => $user->id,
                'nickname' => $user->nickname,
                'grade' => $user->grade ? [
                    'id' => $user->grade->id,
                    'level' => $user->grade->level,
                    'title' => $user->grade->title,
                ] : '',
            ] : '',
            'description' => $this->description,
            'commentable' => $commentable ?? "",

            'count_like' => $this->count_like,
            'count_report' => $this->count_report,
            'is_like' => $this->is_like,
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d'),
        ];
    }
}
