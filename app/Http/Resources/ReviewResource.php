<?php

namespace App\Http\Resources;

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
            'title' => $this->title,
            'description' => $this->description,
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'contact' => $user->contact,
            ] : '',
            'product_id' => $this->product_id,
            'product' => $this->product ? ProductMiniResource::make($this->product) : "",
            'package' => $this->package ? PackageResource::make($this->package) : "",
            'presetProduct' => $this->presetProduct ? [
                'id' => $this->presetProduct->id,
                'product_title' => $this->product_title,
                'option_title' => $this->option_title,
            ] : "",
            'reply' => $this->reply ?? '',
            'reply_at' => $this->reply_at ? Carbon::make($this->reply_at)->format('Y.m.d') : '',

            'img' => $this->img ?? '',
            'imgs' => $this->imgs,
            'photo' => $this->photo,
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d'),
        ];
    }
}
