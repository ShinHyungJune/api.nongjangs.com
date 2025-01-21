<?php

namespace App\Http\Resources;

use App\Enums\TypePackage;
use App\Models\Arr;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PackageSetting */
class PackageSettingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type_package' => $this->type_package,
            'format_type_package' => TypePackage::getLabel($this->type_package),
            'term_week' => $this->term_week,
            'active' => $this->active,
            'will_order_at' => $this->will_order_at ? Carbon::make($this->will_order_at)->format('Y.m.d') : '',
            'retry' => $this->retry,

            'card_id' => $this->card_id,
            'card' => $this->card ? CardResource::make($this->card) : '',
            'delivery_id' => $this->delivery_id,
            'delivery' => $this->delivery ? DeliveryResource::make($this->delivery) : '',
            'first_package_id' => $this->first_package_id,
            'firstPackage' => $this->firstPackage ? PackageResource::make($this->firstPackage) : '',

            'unlike_materials' => MaterialResource::collection($this->materials()->wherePivot('unlike', 1)->get()),
            'format_unlike_materials' => Arr::getArrayToString($this->materials()->wherePivot('unlike', 1)->pluck("title")->toArray()),
        ];
    }
}
