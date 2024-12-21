<?php

namespace App\Http\Controllers\Api;

use App\Enums\KakaoTemplate;
use App\Http\Controllers\Controller;
use App\Http\Requests\FindPasswordRequest;
use App\Http\Resources\UserResource;
use App\Mail\SocialCreated;
use App\Mail\Sample;
use App\Models\EmailVerification;
use App\Models\Kakao;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\VerifyNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class FindPasswordController extends ApiController
{
    /**
     * @group FindPassword(비밀번호 찾기)
     */
    public function store(FindPasswordRequest $request)
    {
        $request['contact'] = str_replace("-", "", $request->contact);

        $user = User::where("contact", $request->contact)
            ->where("ids", $request->ids)
            ->first();

        if(!$user)
            return $this->respondForbidden("가입할 때 입력했던 연락처와 아이디를 다시 확인해주세요.");

        $verifyNumber = VerifyNumber::where('ids', $request->contact)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return throw ValidationException::withMessages([
                "contact" => [
                    "연락처를 인증해주세요."
                ]
            ]);

        $verifyNumber->delete();

        $user->update(['password' => Hash::make($request->password)]);

        return $this->respondSuccessfully(UserResource::make($user));
    }
}
