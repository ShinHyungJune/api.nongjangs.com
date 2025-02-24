<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Memo */
class MemoResource extends JsonResource
{
    public function toArray($request)
    {
        $user = User::withTrashed()->find($this->user_id);

        return [
            'id' => $this->id,
            'description' => $this->description,

            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name
            ] : '',

            'format_created_at' => $this->created_at ? Carbon::make($this->created_at)->format('y-m-d H:i') : "",
        ];
    }
}
