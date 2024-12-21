<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\EmailVerification */
class EmailVerificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'email' => $this->email,
            'code' => $this->code,
            'verified_at' => $this->verified_at,
        ];
    }
}
