<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PackageChangeHistory */
class PackageChangeHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'type' => $this->type,

            'user_id' => $this->user_id,
            'preset_product_id' => $this->preset_product_id,
            'origin_package_id' => $this->origin_package_id,

            'presetProduct' => new PresetProductResource($this->whenLoaded('presetProduct')),
            'originPackage' => new PackageResource($this->whenLoaded('originPackage')),
        ];
    }
}
