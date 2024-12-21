<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Phrase */
class PhraseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'count_use' => $this->count_use,

            'phrase_product_category_id' => $this->phrase_product_category_id,
            'phrase_receiver_category_id' => $this->phrase_receiver_category_id,

            'phraseProductCategory' => PhraseProductCategoryResource::make($this->phraseProductCategory),
            'phraseReceiverCategory' => PhraseReceiverCategoryResource::make($this->phraseReceiverCategory),
        ];
    }
}
