<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Count */
class CountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sum_weight' => $this->sum_weight,
            'sum_store' => $this->sum_store,
            'count_review' => $this->count_review,
            'count_review_package' => $this->count_review_pacakage,
            'average_score_review_package' => $this->average_score_review_package,
            'count_vegetable_story' => $this->count_vegetable_story,
        ];
    }
}
