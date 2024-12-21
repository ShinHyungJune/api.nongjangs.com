<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Estimate */
class EstimateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'company_name' => $this->company_name,
            'name' => $this->name,
            'contact' => $this->contact,
            'title' => $this->title,
            'description' => $this->description,
            'budget' => $this->budget,
            'count' => $this->count,
            'need_finished_at' => $this->need_finished_at ? Carbon::make($this->need_finished_at)->format('Y-m-d') : '',
            'files' => $this->files,
        ];
    }
}
