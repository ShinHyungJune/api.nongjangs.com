<?php

namespace App\Http\Resources;

use App\Enums\StateRefund;
use App\Enums\TypeRefund;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Refund */
class RefundResource extends JsonResource
{
    public function toArray($request)
    {
        $user = User::withTrashed()->find($this->user_id);

        return [
            'id' => $this->id,

            'imgs' => $this->imgs,
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'contact' => $user->contact,
            ] : "",
            'presetProduct' => PresetProductMiniResource::make($this->presetProduct),
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'reason_deny' => $this->reason_deny,
            'state' => $this->state,
            'format_state' => StateRefund::getLabel($this->state),

            'format_processed_at' => $this->processed_at ? Carbon::make($this->processed_at)->format('Y.m.d H:i') : '',
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d H:i'),
        ];
    }
}
