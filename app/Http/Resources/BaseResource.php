<?php

namespace App\Http\Resources;

use App\Utils\StringUtils;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public function format($data): array
    {
        foreach ($data as $key => $value) {
            if ($value instanceof Carbon && !isset($data['format_' . $key])) {
                $data['format_' . $key] = StringUtils::formatDatetime($value);
            }
        }
        if(!isset($data['img']) && $this->img) // img 누락했을 경우 img 추가
            $data['img'] = $this->img;

        if(!isset($data['imgs']) && !empty($this->imgs)) // imgs 누락했을 경우 imgs 추가
            $data['imgs'] = $this->imgs;

        return $data;
    }
}