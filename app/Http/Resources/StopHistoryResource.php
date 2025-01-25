<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\StopHistory */
class StopHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'memo' => $this->memo,

            'package_id' => $this->package_id,

            'package' => new PackageResource($this->whenLoaded('package')),
        ];
    }
}
