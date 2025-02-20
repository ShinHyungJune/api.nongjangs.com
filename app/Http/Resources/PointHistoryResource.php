<?php

namespace App\Http\Resources;

use App\Enums\TypePointHistory;
use App\Models\Coupon;
use App\Models\Point;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PointHistory */
class PointHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        $pointHistoriable = $this->pointHistoriable;

        if($this->point_historiable_type == Point::class)
            $pointHistoriable = PointResource::make($pointHistoriable);

        $point = $this->hasOne(Point::class)->first();

        return [
            'id' => $this->id,

            'type' => $this->type,
            'increase' => $this->increase,
            'point_leave' => $this->point_leave,
            'pointModel' => $this->pointModel ? PointResource::make($this->pointModel) : '',
            'point' => $this->point,
            'memo' => $this->memo,
            'start_expired_at' => $point ? Carbon::make($point->created_at)->format('y-m-d') : '',
            'finish_expired_at' => $point ? Carbon::make($point->expired_at)->format('y-m-d') : '',

            'format_increase' => $this->format_increase,
            'pointHistoriable' => $pointHistoriable,
            'format_title' => $this->format_title,
            'format_type' => TypePointHistory::getLabel($this->type),
            'format_created_at' => $this->created_at ? Carbon::make($this->created_at)->format('Y-m-d H:i') : '',
        ];
    }
}
