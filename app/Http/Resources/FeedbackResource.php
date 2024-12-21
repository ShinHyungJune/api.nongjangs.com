<?php

namespace App\Http\Resources;

use App\Enums\StateFeedback;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Feedback */
class FeedbackResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'admin' => $this->admin,
            'prototypes' => PrototypeResource::collection($this->presetProduct->prototypes),
            'description' => $this->description,
            'name' => $this->admin ? '관리자' : '사용자',
            'check' => $this->check,
            /*'state' => $this->state,
            'format_state' => StateFeedback::getLabel($this->state),*/
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d H:i'),
        ];
    }
}
