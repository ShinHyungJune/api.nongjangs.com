<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\VegetableStory */
class VegetableStoryResource extends JsonResource
{
    public function toArray($request)
    {
        $user = User::withTrashed()->find($this->user_id);

        return [
            'id' => $this->id,
            'description' => $this->description,

            'user_id' => $this->user_id,
            'package_id' => $this->package_id,
            'product_id' => $this->product_id,
            'preset_product_id' => $this->preset_product_id,
            'recipe_id' => $this->recipe_id,

            'user' => $user ? [
                'id' => $user->id,
                'nickname' => $user->nickname,
                'name' => $user->name,
                'grade' => $user->grade ? [
                    'id' => $user->grade->id,
                    'level' => $user->grade->level,
                    'title' => $user->grade->title,
                ] : '',
            ] : '',
            'presetProduct' => $this->presetProduct ? PresetProductMiniResource::make($this->presetProduct) : '',
            'package' => $this->package ? [
                'id' => $this->package->id,
                'count' => $this->package->count,
                'tags' => TagResource::collection($this->package->tags),
            ] : '',
            'product' => $this->product ? [
                'id' => $this->product->id,
                'title' => $this->product->title,
                'tags' => TagResource::collection($this->product->tags),
            ] : '',
            'option' => $this->presetProduct ? [
                'id' => $this->presetProduct->option_id,
                'title' => $this->presetProduct->option_title,
            ] : '',
            'recipe' => $this->recipe ? [
                'id' => $this->recipe->id,
                'title' => $this->recipe->title,
                'img' => $this->recipe->img ?? ''
            ] : '',
            'img' => $this->img ?? '',
            'imgs' => $this->imgs,
            'tags' => TagResource::collection($this->tags),
            'tag_ids' => $this->tags->pluck('id')->toArray(),
            'count_like' => $this->count_like,
            'count_report' => $this->count_report,
            'count_bookmark' => $this->count_bookmark,
            'count_comment' => $this->count_comment,

            'count_preset_product_create' => $this->count_preset_product_create,
            'is_like' => $this->is_like,
            'is_bookmark' => $this->is_bookmark,
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d'),
        ];
    }
}
