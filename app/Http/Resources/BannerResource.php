<?php

namespace App\Http\Resources;

use App\Enums\TypeBanner;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'color' => $this->color,
            'type' => $this->type, // 유형
            'format_type' => TypeBanner::getLabel($this->type),
            'tag' => $this->tag, // 태그
            'title' => $this->title, // 제목
            'description' => $this->description, // 내용
            'url' => $this->url, // 클릭 시 이동할 URL
            'img' => $this->img ?? '', // 이미지
            'order' => $this->order,
        ];
    }
}
