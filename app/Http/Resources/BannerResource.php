<?php

namespace App\Http\Resources;

use App\Enums\TypeBanner;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type, // 유형
            'format_type' => TypeBanner::getLabel($this->type),
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'url' => $this->url,
            'button' => $this->button,
            'color_text' => $this->color_text,
            'color_button' => $this->color_button,
            'started_at' => $this->started_at ? Carbon::make($this->started_at) : '',
            'finished_at' => $this->finished_at ? Carbon::make($this->finished_at) : '',
            'order' => $this->order,
        ];
    }
}
