<?php

namespace App\Http\Resources;

use App\Enums\StateOrder;
use App\Enums\StatePackage;
use App\Enums\TypePackage;
use App\Enums\TypeUser;
use App\Models\Delivery;
use App\Models\Grade;
use App\Models\Like;
use App\Models\PackageSetting;
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
        $recommendUser = null;

        if($request->code_recommend)
            $recommendUser = User::withTrashed()->where('code', $this->code_recommend)->first();

        $delivery = $this->deliveries()->where('main', 1)->first();

        $currentPackagePresetProduct = $this->getCurrentPackagePresetProduct();

        return [
            "id" => $this->id,
            "active" => $this->active,

            'grade' => $this->grade ? GradeResource::make($this->grade) : '',
            "delivery_requirement" => $this->delivery_requirement ?? '',
            "code" => $this->code,

            "admin" => $this->admin ? 1 : 0,

            "social" => $this->social ?? '',

            "email" => $this->email ?? "",
            "name" => $this->name ?? "",
            "nickname" => $this->nickname ?? "",
            "contact" => $this->contact ?? "",

            "agree_promotion" => $this->agree_promotion,
            "code_recommend" => $this->code_recommend,

            "reason" => $this->reason,
            "and_so_on" => $this->and_so_on,

            'count_family' => $this->count_family ?? '',
            'birth' => $this->birth ? Carbon::make($this->birth)->format('Y.m.d') : '',
            'always_use_coupon_for_package' => $this->always_use_coupon_for_package,
            'always_use_point_for_package' => $this->always_use_point_for_package,
            'message' => $this->message ?? '',
            'reason_leave' => $this->reason_leave ?? '',
            'reason_leave_and_so_on' => $this->reason_leave_and_so_on ?? '',

            "point" => $this->point,
            'point_use' => $this->point_use,
            'currentPackagePresetProduct' => $currentPackagePresetProduct ? [
                'id' => $currentPackagePresetProduct->id,
                'package' => [
                    'id' => $currentPackagePresetProduct->package_id,
                    'count' => $currentPackagePresetProduct->package_count,
                ],
            ] : '',

            'packageSetting' => $this->packageSetting ? PackageSettingMiniResource::make($this->packageSetting) : '',
            'count_product' => $this->count_product,
            'count_package' => $this->count_package,
            'count_cart' => $this->cart->presets()->count(),
            'count_alarm' => 0,
            'count_ongoing_preset_product' => $this->count_ongoing_preset_product,
            'count_coupon' => $this->count_coupon,
            'count_total_coupon' => $this->count_total_coupon,
            'count_report' => $this->count_report,
            'count_finish_report' => $this->count_finish_report,
            'count_qna' => $this->count_qna,
            'count_answer_qna' => $this->count_answer_qna,

            'count_package_for_next_grade' => $this->count_package_for_next_grade,
            'price_for_next_grade' => $this->price_for_next_grade,
            'total_order_price' => $this->total_order_price,
            'total_order_count_package' => $this->total_order_count_package,

            'count_recommended' => $this->count_recommended,
            "recommendUser" => $recommendUser ? [
                'id' => $recommendUser->id,
                'name' => $recommendUser->name,
                'nickname' => $recommendUser->nickname,
            ] : '',
            "delivery" => $delivery ? [
                'id' => $delivery->id,
                'address' => $delivery->address,
                'address_detail' => $delivery->address_detail
            ] : "",

            "refund_owner" => $this->refund_owner,
            "refund_bank" => $this->refund_bank,
            "refund_account" => $this->refund_account,

            "created_at" => $this->created_at ? Carbon::make($this->created_at)->format("Y-m-d H:i") : "",
            "format_created_at" => $this->created_at ? Carbon::make($this->created_at)->format("Y.m.d") : "",
            "updated_at" => $this->updated_at ? Carbon::make($this->updated_at)->format("Y-m-d H:i") : "",
            "format_deleted_at" => $this->deleted_at ? Carbon::make($this->deleted_at)->format("Y-m-d H:i") : "",
        ];
    }
}
