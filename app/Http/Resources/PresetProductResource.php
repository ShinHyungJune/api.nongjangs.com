<?php

namespace App\Http\Resources;

use App\Enums\DeliveryCompany;
use App\Enums\StatePackage;
use App\Enums\StatePresetProduct;
use App\Enums\TypeOption;
use App\Enums\TypePackage;
use App\Models\Arr;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PresetProduct */
class PresetProductResource extends JsonResource
{
    public function toArray($request)
    {
        $package = Package::find($this->package_id);

        $user = User::withTrashed()->find($this->preset->user_id);

        return [
            'id' => $this->id,
            'state' => $this->state,
            'format_state' => $this->state ? StatePresetProduct::getLabel($this->state) : '',

            'price' => $this->price,
            'reason_request_cancel' => $this->reason_request_cancel,
            'reason_deny_cancel' => $this->reason_deny_cancel,
            'format_cancel_at' => $this->cancel_at ? Carbon::make($this->cancel_at)->format('Y.m.d H:i') : '',
            'format_request_cancel_at' => $this->request_cancel_at ? Carbon::make($this->request_cancel_at)->format('Y.m.d H:i') : '',

            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'grade' => $user->grade ? [
                    'id' => $user->grade->id,
                    'level' => $user->grade->level,
                    'title' => $user->grade->title,
                ] : '',
            ] : '',
            'order' => $this->preset->order ? [
                'id' => $this->preset->order->id,
                'merchant_uid' => $this->preset->order->merchant_uid,
            ] : '',
            'product' => $this->product_id ? [
                'id' => $this->product_id,
                'img' => $this->product ? $this->product->img : '',
                'title' => $this->product_title,
                'price' => $this->product_price,
                'price_origin' => $this->product_price_origin,
                'requiredOptions' => $this->product ? OptionResource::collection($this->product->options()->where('type', TypeOption::REQUIRED)->get()) : [],
                'additionalOptions' => $this->product ? OptionResource::collection($this->product->options()->where('type', TypeOption::ADDITIONAL)->get()) : [],
                'tags' => $this->product ? TagResource::collection($this->product->tags) : [],
            ] : '',
            'products_price' => $this->products_price,
            'package' => $this->package_id ? [
                'id' => $this->package_id,
                'state' => $this->package ? $this->package->state : '',
                'format_state' => $this->package ? StatePackage::getLabel($this->package->state) : '',
                'name' => $this->package_name,
                'count' => $this->package_count,
                'price' => $this->package_price,
                'type' => $this->package_type,
                'active' => $this->package_active,
                'price_single' => $package ? $package->price_single : '',
                'price_bungle' => $package ? $package->price_bungle : '',
                'format_start_pack_at' => $package && $package->start_pack_at ? Carbon::make($package->start_pack_at)->format('Y.m.d'). '(' . Carbon::make($package->start_pack_at)->isoFormat('ddd') . ')' : '',
                'format_will_delivery_at' => $this->package_will_delivery_at ? Carbon::make($this->package_will_delivery_at)->format('Y.m.d'). '(' . Carbon::make($this->package_will_delivery_at)->isoFormat('ddd') . ')' : '',
                'format_type' => TypePackage::getLabel($this->package_type),
                'tags' => $this->package ? TagResource::collection($this->package->tags) : [],
            ] : '',
            'option' => [
                'id' => $this->option_id,
                'title' => $this->option_title,
                'price' => $this->option_price,
                'type' => $this->option_type,
            ],
            /*'product_title' => $this->product_title,
            'product_price' => $this->product_price,
            'product_price_origin' => $this->product_price_origin,*/
            'price_coupon' => $this->price_coupon,
            'point' => $this->point,
            'count' => $this->count,
            /*'option_title' => $this->option_title,
            'option_price' => $this->option_price,
            'option_type' => $this->option_type,*/

            'preset_id' => $this->preset_id,
            'product_id' => $this->product_id,
            'option_id' => $this->option_id,
            'coupon_id' => $this->coupon_id,

            'delivery_name' => $this->delivery_name,
            'delivery_contact' => $this->delivery_contact,
            'delivery_address' => $this->delivery_address,
            'delivery_address_detail' => $this->delivery_address_detail,
            'delivery_address_zipcode' => $this->delivery_address_zipcode,
            'delivery_requirement' => $this->delivery_requirement,
            'delivery_number' => $this->delivery_number,
            'delivery_company' => $this->delivery_company,
            'format_delivery_company' => $this->delivery_company ? DeliveryCompany::getLabel($this->delivery_company) : '',
            'delivery_at' => $this->delivery_at ? Carbon::make($this->delivery_at)->format('Y.m.d') : '',
            'format_title' => $this->format_title,
            'format_created_at' => $this->preset->order ? Carbon::make($this->preset->order->created_at)->format('Y.m.d') : '',

            'materials' => MaterialResource::collection($this->materials),
            'format_materials' => Arr::getArrayToString($this->materials->pluck('title')->toArray()),
            'canLatePackage' => $this->canLatePackage ? [
                'id' => $this->canLatePackage->id,
                'format_will_delivery_at' => Carbon::make($this->canLatePackage->will_delivery_at)->format('Y.m.d'). '(' . Carbon::make($this->canLatePackage->will_delivery_at)->isoFormat('ddd') . ')',
            ] : '',
            'canFastPackage' => $this->canFastPackage ? [
                'id' => $this->canFastPackage->id,
                'format_will_delivery_at' => Carbon::make($this->canFastPackage->will_delivery_at)->format('Y.m.d'). '(' . Carbon::make($this->canFastPackage->will_delivery_at)->isoFormat('ddd') . ')',
            ] : '',
            'can_confirm' => $this->can_confirm,
            'can_review' => $this->can_review,
            'can_cancel' => $this->can_cancel,
            'can_request_cancel' => $this->can_request_cancel,
            'can_update_materials' => $this->can_update_materials,

            'count_review' => $this->count_review,
            'count_vegetable_story' => $this->count_vegetable_story,
            'format_refund_method' => $this->format_refund_method,
            'format_delivery_tracks' => $this->format_delivery_tracks,
            'price_delivery' => 0,
            /*'preset' => new PresetResource($this->whenLoaded('preset')),
            'product' => new ProductResource($this->whenLoaded('product')),
            'option' => new OptionResource($this->whenLoaded('option')),
            'coupon' => new CouponResource($this->whenLoaded('coupon')),*/
        ];
    }
}
