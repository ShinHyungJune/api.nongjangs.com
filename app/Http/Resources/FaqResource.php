<?php

namespace App\Http\Resources;

use App\Models\Faq;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Faq */
class FaqResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,

            'faq_category_id' => $this->faq_category_id,
            'faqCategory' => FaqCategoryResource::make($this->faqCategory),

            'title' => $this->title,
            'description' => $this->description,

            'format_created_at' => Carbon::make($this->created_at)->format("Y.m.d"),
        ];
    }
}
