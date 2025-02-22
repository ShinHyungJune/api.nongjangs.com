<?php

namespace App\Http\Resources;

use App\Enums\TypeOption;
use App\Enums\TypePackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Review */
class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        $user = User::withTrashed()->find($this->user_id);

        /*$name = $user->name;

        if(!mb_check_encoding($name, 'UTF-8'))
            $name = mb_convert_encoding($user->name, 'UTF-8', 'auto');*/

        return [
            'id' => $this->id,
            'best' => $this->best,
            'score' => $this->score,
            'point' => $this->point,
            'description' => $this->description,
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'nickname' => $user->nickname,
                'grade' => $user->grade ? [
                    'id' => $user->grade->id,
                    'level' => $user->grade->level,
                    'title' => $user->grade->title,
                ] : ''
            ] : '',
            'product_id' => $this->product_id,
            'product' => $this->product ? ProductMiniResource::make($this->product) : "",
            'package' => $this->package ? PackageMiniResource::make($this->package) : "",
            'presetProduct' => $this->presetProduct ? PresetProductMiniResource::make($this->presetProduct) : "",
            'reply' => $this->reply ?? '',
            'reply_at' => $this->reply_at,
            'format_reply_at' => $this->reply_at ? Carbon::make($this->reply_at)->format('Y.m.d') : '',

            'img' => $this->img ?? '',
            'imgs' => $this->imgs,
            'photo' => $this->photo,
            'hide' => $this->hide,
            'format_display' => $this->format_display,
            'is_like' => $this->is_like,
            'is_bookmark' => $this->is_bookmark,
            'count_like' => $this->count_like,
            'count_bookmark' => $this->count_bookmark,
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d'),
        ];
    }
}
