<?php

namespace App\Http\Resources;

use App\Enums\StatePresetProduct;
use App\Enums\TypeDelivery;
use App\Models\PresetProduct;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Preset */
class PresetResource extends JsonResource
{
    public function toArray($request)
    {
        $order = $this->order;

        return [
            'id' => $this->id,

            'presetProducts' => PresetProductMiniResource::collection($this->presetProducts),

            'can_order' => $this->can_order,

            'order' => $order ? [
                'id' => $order->id,
                'merchant_uid' => $order->merchant_uid,
                'format_created_at' => $order->created_at ? Carbon::make($this->created_at)->format('Y.m.d H:i') : '',
            ] : '',

        ];
    }
}
