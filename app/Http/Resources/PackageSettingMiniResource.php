<?php

namespace App\Http\Resources;

use App\Enums\TypePackage;
use App\Models\Arr;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageSettingMiniResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
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
            'format_unlike_materials' => Arr::getArrayToString($this->materials()->wherePivot('unlike', 1)->pluck("title")->toArray()),
        ];
    }
}
