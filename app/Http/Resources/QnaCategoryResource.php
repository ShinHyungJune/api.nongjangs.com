<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\QnaCategory */
class QnaCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
