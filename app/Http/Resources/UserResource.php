<?php

namespace App\Http\Resources;

use App\Enums\Gender;
use App\Enums\StateOrder;
use App\Enums\State;
use App\Enums\StateUser;
use App\Enums\TypeUser;
use App\Models\Delivery;
use App\Models\Like;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "id" => $this->id,

            'tax_business_number' => $this->tax_business_number,
            'tax_company_title' => $this->tax_company_title,
            'tax_company_president' => $this->tax_company_president,
            'tax_company_type' => $this->tax_company_type,
            'tax_company_category' => $this->tax_company_category,
            'tax_email' => $this->tax_email,
            'tax_name' => $this->tax_name,
            'tax_contact' => $this->tax_contact,
            'tax_address' => $this->tax_address,

            "count_valid_coupon" => $this->count_valid_coupon,
            "count_ongoing_preset_product" => $this->count_ongoing_preset_product,
            "format_ongoing_preset_products" => $this->format_ongoing_preset_products,
            // 'ongoingPresetProducts' => PresetProductMiniResource::collection($this->ongoingPresetProducts),

            "admin" => $this->admin ? 1 : 0,

            "social" => $this->social ?? '',

            "type" => $this->type,
            "format_type" => TypeUser::getLabel($this->type),

            "point" => $this->point,
            "ids" => $this->ids ?? "",
            "email" => $this->email ?? "",
            "name" => $this->name ?? "",
            "contact" => $this->contact ?? "",
            "address" => $this->address ?? "",
            "address_detail" => $this->address_detail ?? "",
            "address_zipcode" => $this->address_zipcode ?? "",

            "business_number" => $this->business_number ?? "",
            "company_title" => $this->company_title ?? "",
            "company_president" => $this->company_president ?? "",
            "company_size" => $this->company_size ?? "",
            "company_type" => $this->company_type ?? "",
            "company_category" => $this->company_category ?? "",

            "agree_promotion_sms" => $this->agree_promotion_sms,
            "agree_promotion_email" => $this->agree_promotion_email,
            "agree_promotion_call" => $this->agree_promotion_call,

            "reason" => $this->reason,
            "and_so_on" => $this->and_so_on,

            "created_at" => $this->created_at ? Carbon::make($this->created_at)->format("Y-m-d H:i") : "",
            "format_created_at" => $this->created_at ? Carbon::make($this->created_at)->format("Y.m.d") : "",
            "updated_at" => $this->updated_at ? Carbon::make($this->updated_at)->format("Y-m-d H:i") : "",
            "deleted_at" => $this->deleted_at ? Carbon::make($this->deleted_at)->format("Y-m-d H:i") : "",
        ];
    }
}
