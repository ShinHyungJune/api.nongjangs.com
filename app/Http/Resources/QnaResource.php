<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Qna */
class QnaResource extends JsonResource
{
    public function toArray($request)
    {
        $user = User::withTrashed()->find($this->user_id);

        return [
            'id' => $this->id,

            'user_id' => $this->user_id,
            'user' => $user ? [
                'id' => $user->id,
                'ids' => $user->ids,
                'name' => $user->name,
                'nickname' => $user->nickname,
                'contact' => $user->contact,
                'grade' => $user->grade ? [
                    'id' => $user->grade->id,
                    'level' => $user->grade->level,
                    'title' => $user->grade->title,
                ] : '',
            ] : '',
            'answerUser' => $user ? [
                'id' => $user->id,
                'ids' => $user->ids,
                'name' => $user->name,
                'nickname' => $user->nickname,
                'contact' => $user->contact,
            ] : '',
            'qna_category_id' => $this->qna_category_id,
            'qnaCategory' => QnaCategoryResource::make($this->qnaCategory),

            'title' => $this->title,
            'description' => $this->description,
            'answer' => $this->answer ?? "",
            'imgs' => $this->imgs,

            /*"state" => $this->state,
            "format_state" => $this->format_state,*/


            "format_created_at" => Carbon::make($this->created_at)->format("Y.m.d"),
        ];
    }
}
