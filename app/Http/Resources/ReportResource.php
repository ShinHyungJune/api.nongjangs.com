<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Report */
class ReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,

            'report_category_id' => $this->report_category_id,

            'reportCategory' => ReportCategoryResource::make($this->reportCategory),
            'created_at' => $this->created_at,
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d H:i')
        ];
    }
}
