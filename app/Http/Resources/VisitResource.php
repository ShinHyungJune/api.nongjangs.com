<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Visit */
class VisitResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'ip' => $this->ip,

            'user_id' => $this->user_id,
            'company_id' => $this->company_id,

            'company' => new CompanyResource($this->whenLoaded('company')),
        ];
    }
}
