<?php

namespace App\Http\Resources;

use App\Models\Faq;
use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Notice */
class NoticeResource extends JsonResource
{
    public function toArray($request)
    {
        $next = Notice::find(Notice::where('id', '<', $this->id)->max('id'));

        $prev = Notice::find(Notice::where('id', '>', $this->id)->min('id'));

        return [
            'id' => $this->id,
            'important' => $this->important,
            'title' => $this->title,
            'description' => $this->description,
            'count_view' => $this->count_view,

            'next' => $next ? [
                'id' => $next->id,
                'title' => $next->title,
            ] : '',
            'prev' => $prev ? [
                'id' => $prev->id,
                'title' => $prev->title,
            ] : '',

            'format_year' => $this->format_year,
            'format_month' => $this->format_month,
            'format_date' => $this->format_date,
            'format_created_at' => Carbon::make($this->created_at)->format("Y.m.d"),
        ];
    }
}
