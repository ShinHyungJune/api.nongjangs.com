<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Recipe */
class RecipeResource extends JsonResource
{
    public function toArray($request)
    {
        $user = User::withTrashed()->find($this->user_id);

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'materials' => $this->materials,
            'seasonings' => $this->seasonings,
            'ways' => $this->ways,
            'count_view' => $this->count_view,
            'created_at' => $this->created_at,

            'user' =>  UserResource::make($user),
            'img' => $this->img,
            'imgs' => $this->imgs,
            'tags' => TagResource::collection($this->tags),
            'format_materials' => $this->format_materials,
            'format_seasonings' => $this->format_seasonings,
            'format_ways' => $this->format_ways,
            'count_like' => $this->likes()->count(),
            'count_bookmark' => $this->bookmarks()->count(),
            'is_like' => $this->is_like,
            'is_bookmark' => $this->is_bookmark,
            'format_created_at' => $this->created_at ? Carbon::make($this->created_at)->format('Y-m-d') : '',
        ];
    }
}
