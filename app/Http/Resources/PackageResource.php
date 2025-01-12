<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Package */
class PackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'count' => $this->count,
            'will_deliveried_at' => $this->will_deliveried_at,
            'format_will_deliveried_at' => $this->will_deliveried_at ? Carbon::make('will_deliveried_at')->format('Y.m.d') : '',
            'tax' => $this->tax,
            'start_pack_wait_at' => $this->start_pack_wait_at,
            'format_start_pack_wait_at' => $this->start_pack_wait_at ? Carbon::make('start_pack_wait_at')->format('Y.m.d') : '',
            'finish_pack_wait_at' => $this->finish_pack_wait_at,
            'format_finish_pack_wait_at' => $this->finish_pack_wait_at ? Carbon::make('finish_pack_wait_at')->format('Y.m.d') : '',
            'start_pack_at' => $this->start_pack_at,
            'format_start_pack_at' => $this->start_pack_at ? Carbon::make('start_pack_at')->format('Y.m.d') : '',
            'finish_pack_at' => $this->finish_pack_at,
            'format_finish_pack_at' => $this->finish_pack_at ? Carbon::make('finish_pack_at')->format('Y.m.d') : '',
            'start_delivery_ready_at' => $this->start_delivery_ready_at,
            'format_start_delivery_ready_at' => $this->start_delivery_ready_at ? Carbon::make('start_delivery_ready_at')->format('Y.m.d') : '',
            'finish_delivery_ready_at' => $this->finish_delivery_ready_at,
            'format_finish_delivery_ready_at' => $this->finish_delivery_ready_at ? Carbon::make('finish_delivery_ready_at')->format('Y.m.d') : '',
            'start_will_out_at' => $this->start_will_out_at,
            'format_start_will_out_at' => $this->start_will_out_at ? Carbon::make('start_will_out_at')->format('Y.m.d') : '',
            'finish_will_out_at' => $this->finish_will_out_at,
            'format_finish_will_out_at' => $this->finish_will_out_at ? Carbon::make('finish_will_out_at')->format('Y.m.d') : '',
        ];
    }
}
