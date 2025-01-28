<?php

namespace App\Http\Resources;

use App\Enums\TypePointHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PointHistory */
class PointHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'type' => $this->type,
            'increase' => $this->increase,
            'point_leave' => $this->point_leave,
            'point' => $this->point,
            'memo' => $this->memo,

            'format_increase' => $this->format_increase,
            'pointHistoriable' => $this->pointHistoriable,
            'format_type' => TypePointHistory::getLabel($this->type),
            'format_created_at' => $this->created_at ? Carbon::make($this->created_at)->format('Y-m-d H:i') : '',
        ];
    }
}
