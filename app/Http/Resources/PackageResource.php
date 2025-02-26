<?php

namespace App\Http\Resources;

use App\Enums\StatePackage;
use App\Enums\TypePackage;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Package */
class PackageResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'count' => $this->count,
            'will_delivery_at' => $this->will_delivery_at ? $this->will_delivery_at->toISOString() : '',
            'format_will_delivery_at' => $this->will_delivery_at ? Carbon::make($this->will_delivery_at)->format('Y.m.d') . '(' . Carbon::make($this->will_delivery_at)->isoFormat('ddd') . ')' : '',
            'tax' => $this->tax,
            'start_pack_wait_at' => $this->start_pack_wait_at ? $this->start_pack_wait_at->toISOString() : '',
            'format_start_pack_wait_at' => $this->start_pack_wait_at ? Carbon::make($this->start_pack_wait_at)->format('Y.m.d H:i') : '',
            'finish_pack_wait_at' => $this->finish_pack_wait_at ? $this->finish_pack_wait_at->toISOString() : '',
            'format_finish_pack_wait_at' => $this->finish_pack_wait_at ? Carbon::make($this->finish_pack_wait_at)->format('Y.m.d H:i') : '',
            'start_pack_at' => $this->start_pack_at ? $this->start_pack_at->toISOString() : '',
            'format_start_pack_at' => $this->start_pack_at ? Carbon::make($this->start_pack_at)->format('Y.m.d H:i') : '',
            'finish_pack_at' => $this->finish_pack_at ? $this->finish_pack_at->toISOString() : '',
            'format_finish_pack_at' => $this->finish_pack_at ? Carbon::make($this->finish_pack_at)->format('Y.m.d H:i') : '',
            'start_delivery_ready_at' => $this->start_delivery_ready_at ? $this->start_delivery_ready_at->toISOString() : '',
            'format_start_delivery_ready_at' => $this->start_delivery_ready_at ? Carbon::make($this->start_delivery_ready_at)->format('Y.m.d H:i') : '',
            'finish_delivery_ready_at' => $this->finish_delivery_ready_at ? $this->finish_delivery_ready_at->toISOString() : '',
            'format_finish_delivery_ready_at' => $this->finish_delivery_ready_at ? Carbon::make($this->finish_delivery_ready_at)->format('Y.m.d H:i') : '',
            'start_will_out_at' => $this->start_will_out_at ? $this->start_will_out_at->toISOString() : '',
            'format_start_will_out_at' => $this->start_will_out_at ? Carbon::make($this->start_will_out_at)->format('Y.m.d H:i') : '',
            'finish_will_out_at' => $this->finish_will_out_at ? $this->finish_will_out_at->toISOString() : '',
            'format_finish_will_out_at' => $this->finish_will_out_at ? Carbon::make($this->finish_will_out_at)->format('Y.m.d H:i') : '',
            'packageMaterials' => PackageMaterialResource::collection($this->packageMaterials),
            'price_single' => $this->price_single,
            'price_bungle' => $this->price_bungle,
            'state' => $this->state,
            'format_state' => StatePackage::getLabel($this->state),
            'recipes' => $this->recipes()->select(['recipes.id', 'recipes.title'])->get()->toArray(),
        ];
    }
}
