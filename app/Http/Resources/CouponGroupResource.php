<?php

namespace App\Http\Resources;

use App\Enums\MomentCouponGroup;
use App\Enums\TargetCouponGroup;
use App\Enums\TypeCouponGroup;
use App\Enums\TypeDiscount;
use App\Enums\TypeExpire;
use App\Enums\TypePackage;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CouponGroup */
class CouponGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'moment' => $this->moment,
            'format_moment' => $this->moment ? MomentCouponGroup::getLabel($this->moment) : '',
            'type' => $this->type,
            'format_type' => $this->type ? TypeCouponGroup::getLabel($this->type) : '',
            'type_package' => $this->type_package,
            'format_type_package' => $this->type_package ? TypePackage::getLabel($this->type_package) : '',
            'all_product' => $this->all_product,
            'target' => $this->target,
            'format_target' => $this->target ? TargetCouponGroup::getLabel($this->target) : '',
            'min_order' => $this->min_order,
            'type_discount' => $this->type_discount,
            'format_type_discount' => $this->type_discount ? TypeDiscount::getLabel($this->type_discount) : '',
            'value' => $this->value,
            'max_price_discount' => $this->max_price_discount,
            'min_price_order' => $this->min_price_order,
            'type_expire' => $this->type_expire,
            'format_type_expire' => $this->type_expire ? TypeExpire::getLabel($this->type_expire) : '',
            'started_at' => $this->started_at,
            'format_started_at' => $this->started_at ? Carbon::make($this->started_at)->format('Y.m.d H:i') : '',
            'finished_at' => $this->finished_at,
            'format_finished_at' => $this->finished_at ? Carbon::make($this->finished_at)->format('Y.m.d H:i') : '',
            'days' => $this->days,
            'grade_id' => $this->grade_id,
            'has' => $this->has,
            'grade' => $this->grade ? [
                'id' => $this->grade->id,
                'title' => $this->grade->title,
                'level' => $this->grade->level,
            ] : '',

            'count_use' => $this->count_use,
            'count_has' => $this->count_has,

            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d'),
        ];
    }
}
