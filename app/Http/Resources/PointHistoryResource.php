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
        $order = $this->order;
        $user = User::withTrashed()->find($this->user_id);

        return [
            'id' => $this->id,

            'type' => $this->type,
            'increase' => $this->increase,
            'point_current' => $this->point_current,
            'point' => $this->point,
            'memo' => $this->memo,

            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'contact' => $user->contact,
            ] : '',

            'format_increase' => $this->format_increase,
            'order' => $order ? [
                'id' => $order->id,
                'merchant_uid' => $order->merchant_uid,
                'format_created_at' => Carbon::make($order->created_at)->format('Y.m.d H:i'),
            ] : '',
            'format_type' => TypePointHistory::getLabel($this->type),
            'format_created_at' => $this->created_at ? Carbon::make($this->created_at)->format('Y-m-d H:i') : '',
        ];
    }
}
