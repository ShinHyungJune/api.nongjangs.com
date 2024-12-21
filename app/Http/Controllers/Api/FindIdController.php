<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FindIdRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\VerifyNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FindIdController extends ApiController
{
    /**
     * @group FindId(아이디 찾기)
     * @responseFile storage/responses/findId.json
     */
    public function store(FindIdRequest $request)
    {
        $user = User::where("contact", $request->contact)
            ->first();

        if(!$user)
            return $this->respondForbidden("해당 정보로 가입된 계정이 존재하지 않습니다.");

        $verifyNumber = VerifyNumber::where('ids', $request->contact)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return throw ValidationException::withMessages([
                "contact" => [
                    "연락처를 인증해주세요."
                ]
            ]);

        $verifyNumber->delete();

        return $this->respondSuccessfully(['ids' => $user->ids]);
    }
}
