<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Alarm */
class AlarmResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'type' => $this->type,
            'contact' => $this->contact,
            'email' => $this->email,

            'user_id' => $this->user_id,
            'preset_product_id' => $this->preset_product_id,
            'order_id' => $this->order_id,
            'preset_id' => $this->preset_id,
            'qna_id' => $this->qna_id,
            'prototype_id' => $this->prototype_id,
            'feedback_id' => $this->feedback_id,
            'estimate_id' => $this->estimate_id,

            'preset' => new PresetResource($this->whenLoaded('preset')),
            'qna' => new QnaResource($this->whenLoaded('qna')),
            'prototype' => new PrototypeResource($this->whenLoaded('prototype')),
            'feedback' => new FeedbackResource($this->whenLoaded('feedback')),
            'estimate' => new EstimateResource($this->whenLoaded('estimate')),
        ];
    }
}
