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

            "admin" => $this->admin ? 1 : 0,

            "social" => $this->social ?? '',

            "email" => $this->email ?? "",
            "name" => $this->name ?? "",
            "contact" => $this->contact ?? "",

            "agree_promotion" => $this->agree_promotion,
            "code_recommend" => $this->code_recommend,
            "point" => $this->point,

            "address" => $this->address ?? "",
            "address_detail" => $this->address_detail ?? "",
            "address_zipcode" => $this->address_zipcode ?? "",

            "reason" => $this->reason,
            "and_so_on" => $this->and_so_on,

            "created_at" => $this->created_at ? Carbon::make($this->created_at)->format("Y-m-d H:i") : "",
            "format_created_at" => $this->created_at ? Carbon::make($this->created_at)->format("Y.m.d") : "",
            "updated_at" => $this->updated_at ? Carbon::make($this->updated_at)->format("Y-m-d H:i") : "",
            "deleted_at" => $this->deleted_at ? Carbon::make($this->deleted_at)->format("Y-m-d H:i") : "",
        ];
    }
}
