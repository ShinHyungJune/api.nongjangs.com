<?php

namespace App\Http\Controllers\Api;

use App\Enums\TypePointHistory;
use App\Enums\TypeUser;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Arr;
use App\Models\Download;
use App\Models\EmailVerification;
use App\Models\Refund;
use App\Models\Social;
use App\Models\User;
use App\Models\VerifyNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\CountValidator\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends ApiController
{
    /**
     * @group User(사용자)
     * @responseFile storage/responses/login.json
     */
    public function login(UserRequest $request)
    {
        // 소셜로그인 시도 시
        if($request->token && auth()->user()){
            return $this->respondSuccessfully([
                "token" => $request->token,
                "user" => UserResource::make(auth()->user())
            ]);
        }

        $data = $request->validated();

        // $user = User::where("email", $request->email)->first();

        $token = auth()->attempt($request->only("ids", "password"));

        if($token) {
            return $this->respondSuccessfully([
                "token" => $token,
                "user" => UserResource::make(auth()->user())
            ]);
        }

        return throw ValidationException::withMessages([
            "ids" => [
                __("socialLogin.invalid")
            ]
        ]);
    }

    /**
     * @group User(사용자)
     * @responseFile storage/responses/login.json
     */
    public function store(UserRequest $request)
    {
        $request["contact"] = preg_replace('/[\s-]+/', '', $request->contact);

        $data = $request->validated();

        $verifyNumber = VerifyNumber::where('ids', $request->contact)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return throw ValidationException::withMessages([
                "contact" => [
                    "연락처를 인증해주세요."
                ]
            ]);

        $verifyNumber->delete();

        $user = User::create(array_merge($data, [
            'type' => $request->type,
            'password' => $request->password ? Hash::make($request->password) : null,
            'point' => $request->social_id ? 0 : User::$createPoint,
        ]));

        if(!$request->social_id)
            $user->pointHistories()->create([
                'point' => User::$createPoint,
                'increase' => 1,
                'type' => TypePointHistory::USER_CREATED,
            ]);

        /*if(is_array($request->file("img"))){
            foreach($request->file("img") as $file){
                $user->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }*/

        Auth::login($user);

        $token = JWTAuth::fromUser($user);

        return $this->respondSuccessfully([
            'user' => UserResource::make($user),
            'token' => $token,
        ], "성공적으로 가입되었습니다.");
    }

    /**
     * @group User(사용자)
     * @responseFile storage/responses/user.json
     */
    public function show()
    {
        return $this->respondSuccessfully(UserResource::make(auth()->user()));
    }

    public function updateSocial(Request $request)
    {
        $request->validate([
            "social_id" => "required|string|max:50000",
            "social_platform" => "required|string|max:50000",
        ]);

        $social = Social::where("social_id", $request->social_id)
            ->where("social_platform", $request->social_platform)
            ->first();

        if($social && $social->user_id != auth()->id())
            return $this->respondForbidden("이미 다른 계정에 연결되어있습니다.");

        if(!$social)
            auth()->user()->socials()->create([
                "social_id" => $request->social_id,
                "social_platform" => $request->social_platform
            ]);

        return $this->respondSuccessfully();
    }


    public function openSocialLoginPop($social)
    {
        return Socialite::driver($social)->stateless()->redirect();
    }

    public function socialLogin(Request $request, $social)
    {
        $socialUser = Socialite::driver($social)->stateless()->user();

        $user = User::where("social_id", $socialUser->id)->where("social_platform", $social)->first();

        if(!$user) {
            if($socialUser->contact){
                $user = User::where('contact', $socialUser->contact)->first();

                if($user){
                    $message = "이미 해당 연락처로 가입된 이력이 있습니다.";

                    return redirect(config("app.client_url") . "/?message=" . $message);
                }
            }

            $data = json_encode([
                "id" => $socialUser->id,
                "platform" => $social,
                "contact" => $socialUser->contact,
                "email" => $socialUser->email,
                "name" => $socialUser->name ?? ($socialUser->nickname ?? '임시이름'),
                "nickname" => $socialUser->nickname ?? $socialUser->name,
            ]);

            if($socialUser->contact){
                $user = User::create([
                    "social_id" => $socialUser->id,
                    "social_platform" => $social,
                    "contact" => $socialUser->contact,
                    "email" => $socialUser->email,
                    "name" => $socialUser->name ?? ($socialUser->nickname ?? '임시이름'),
                ]);

                $token = JWTAuth::fromUser($user);

                return redirect(config("app.client_url")."/login?token=".$token);
            }

            /*if($user) {
                $message = "이미 {$user->format_social} 로그인으로 가입된 이력이 있습니다.";

                return redirect(config("app.client_url") . "/users/create?success=0&message=" . $message);
            }*/

            return redirect(config("app.client_url") . "/users/create?socialUser=".$data);
        }

        $token = JWTAuth::fromUser($user);

        return redirect(config("app.client_url")."/login?token=".$token);
    }

    /**
     * @group User(사용자)
     * @responseFile storage/responses/user.json
     */
    public function update(UserRequest $request)
    {
        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'address_detail' => $request->address_detail,
            'address_zipcode' => $request->address_zipcode,
            'contact' => $request->contact,
        ];

        if(auth()->user()->contact != $request->contact){
            $verifyNumber = VerifyNumber::where('ids', $request->contact)
                ->where('verified', true)->first();

            if(!$verifyNumber)
                return throw ValidationException::withMessages([
                    "contact" => [
                        "연락처를 인증해주세요."
                    ]
                ]);

            $verifyNumber->delete();
        }

        if($request->password)
            $data['password'] = Hash::make($request->password);

        auth()->user()->update($data);

        return $this->respondSuccessfully(UserResource::make(auth()->user()));
    }

    /**
     * @group User(사용자)
     */
    public function destroy(UserRequest $request)
    {
        auth()->user()->update([
            'reason' => $request->reason,
            'and_so_on' => $request->and_so_on,
        ]);

        auth()->user()->delete();

        return $this->respondSuccessfully();
    }

    /**
     * @group User(사용자)
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        auth()->user()->update(["can_use_at" => null]);

        Auth::guard("web")->logout();

        return $this->respondSuccessfully();
    }

    public function updatePushToken(Request $request)
    {
        if($request->push_token)
            auth()->user()->update(["push_token" => $request->push_token]);

        return $this->respondSuccessfully();
    }
}
