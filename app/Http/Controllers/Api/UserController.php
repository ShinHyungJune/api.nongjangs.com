<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatePresetProduct;
use App\Enums\TypePointHistory;
use App\Enums\TypeUser;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Arr;
use App\Models\Download;
use App\Models\EmailVerification;
use App\Models\PointHistory;
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
    /** 로그인
     * @group 사용자
     * @subgroup User(사용자)
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

        $token = auth()->attempt($request->only("email", "password"));

        if($token) {
            return $this->respondSuccessfully([
                "token" => $token,
                "user" => UserResource::make(auth()->user())
            ]);
        }

        return throw ValidationException::withMessages([
            "email" => [
                __("socialLogin.invalid")
            ]
        ]);
    }

    /** 회원가입
     * @group 사용자
     * @subgroup User(사용자)
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
            'password' => $request->password ? Hash::make($request->password) : null,
        ]));

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

    /** 상세
     * @group 사용자
     * @subgroup User(사용자)
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


    public function openSocialLoginPop($social, Request $request)
    {
        if ($request->has('redirect'))
            session(['redirect' => $request->query('redirect')]);

        return Socialite::driver($social)->stateless()->redirect();
    }

    public function socialLogin(Request $request, $social)
    {
        $redirectPath = session()->pull('redirect', '');

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

            if($socialUser->email){
                $user = User::where('email', $socialUser->email)->first();

                if($user){
                    $message = "이미 해당 이메일로 가입된 이력이 있습니다.";

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

                return redirect(config("app.client_url")."/login?token=".$token.'&redirect='.$redirectPath);
            }

            /*if($user) {
                $message = "이미 {$user->format_social} 로그인으로 가입된 이력이 있습니다.";

                return redirect(config("app.client_url") . "/users/create?success=0&message=" . $message);
            }*/

            return redirect(config("app.client_url") . "/users/create?socialUser=".$data.'&redirect='.$redirectPath);
        }

        $token = JWTAuth::fromUser($user);

        return redirect(config("app.client_url")."/login?token=".$token.'&redirect='.$redirectPath);
    }

    /** 수정
     * @group 사용자
     * @subgroup User(사용자)
     * @responseFile storage/responses/user.json
     */
    public function update(UserRequest $request)
    {
        $data = $request->validated();

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

    /** 삭제
     * @group 사용자
     * @subgroup User(사용자)
     */
    public function destroy(UserRequest $request)
    {
        if(auth()->user()->ongoingPresetProducts()->count() > 0)
            return $this->respondForbidden('진행중인 출고가 있어 탈퇴가 불가능합니다. 고객센터에 문의해주세요.');

        auth()->user()->update([
            'reason' => $request->reason,
            'and_so_on' => $request->and_so_on,
        ]);

        $packageSetting = auth()->user()->packageSetting;

        // 꾸러미 비활성처리
        if($packageSetting)
            $packageSetting->update(['active' => 0]);

        auth()->user()->delete();

        return $this->respondSuccessfully();
    }

    /** 로그아웃
     * @group 사용자
     * @subgroup User(사용자)
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

    /** 추천인 등록
     * @group 사용자
     * @subgroup User(사용자)
     */
    public function updateCodeRecommend(UserRequest $request)
    {
        $user = User::where('code', $request->code_recommend)->first();

        if(!$user)
            return $this->respondForbidden('유효하지 않은 추천인 코드입니다.');

        if(!$user->id == auth()->id())
            return $this->respondForbidden('자기 자신을 추천할 수 없습니다.');

        if(auth()->user()->code_recommend)
            return $this->respondForbidden('이미 추천인 코드를 등록한적 있습니다.');

        $point = User::$recommendPoint;

        auth()->user()->givePoint($point, TypePointHistory::USER_RECOMMEND, $user);
        $user->givePoint($point, TypePointHistory::USER_RECOMMENDED, auth()->user());

        $user->update(['code_recommend' => $request->code_recommend]);

        return $this->respondSuccessfully();
    }

    /** 비밀번호 변경
     * @group 사용자
     * @subgroup User(사용자)
     */
    public function updatePassword(UserRequest $request)
    {
        if(!Hash::check($request->password, auth()->user()->password))
            return $this->respondForbidden('기존 비밀번호가 일치하지 않습니다.');

        auth()->user()->update(['password' => Hash::make($request->password_new)]);

        return $this->respondSuccessfully();
    }

    /** 비밀번호 초기화
     * @group 사용자
     * @subgroup User(사용자)
     */
    public function clearPassword(UserRequest $request)
    {
        $user = User::where('contact', $request->contact)
            ->where('email', $request->email)
            ->first();

        if(!$user)
            return $this->respondForbidden('이메일 및 연락처와 매칭되는 계정정보가 없습니다.');

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

        return $this->respondSuccessfully();
    }

    /** 아이디 찾기
     * @group 사용자
     * @subgroup User(사용자)
     * @responseFile storage/responses/findId.json
     */
    public function findId(UserRequest $request)
    {
        $verifyNumber = VerifyNumber::where('ids', $request->contact)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return throw ValidationException::withMessages([
                "contact" => [
                    "연락처를 인증해주세요."
                ]
            ]);

        $verifyNumber->delete();

        $user = User::where('contact', $request->contact)->first();

        if(!$user)
            return $this->respondForbidden('유효하지 않은 연락처입니다.');

        return $this->respondSuccessfully([
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    /** 쿠폰 자동 적용 여부 수정
     * @group 사용자
     * @subgroup User(사용자)
     */
    public function updateAlwaysUseCouponForPackage(UserRequest $request)
    {
        auth()->user()->update(['always_use_coupon_for_package' => $request->always_use_coupon_for_package]);

        return $this->respondSuccessfully();
    }

    /** 적립금 자동 적용 여부 수정
     * @group 사용자
     * @subgroup User(사용자)
     */
    public function updateAlwaysUsePointForPackage(UserRequest $request)
    {
        auth()->user()->update(['always_use_point_for_package' => $request->always_use_point_for_package]);

        return $this->respondSuccessfully();
    }
}
