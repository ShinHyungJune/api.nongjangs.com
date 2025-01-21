<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->route()->getActionMethod();
        $admin = strpos($this->route()->getPrefix(), 'admin') !== false;

        if($admin){
            switch ($method){
                case 'index':
                    return [
                        'word' => ['nullable', 'string', 'max:500']
                    ];

                case 'store':
                    return [
          
                    ];

                case 'update':
                    return [

                    ];

                case 'destroy':
                    return [
                        'ids' => ['required', 'array'],
                    ];

                default:
                    return [];
            }
        }else{
            switch ($method){
                case "login":
                    return [
                        "token" => "nullable|string|max:500",
                        "ids" => "nullable|string|max:500",
                        "password" => "nullable|string|max:500",
                    ];

                case "store":
                    if($this->get('social_id'))
                        return [
                            "social_id" => "required|string|max:500",
                            "social_platform" => "required|string|max:500",

                            'email' => 'nullable|email|max:500',
                            'name' => 'nullable|string|max:500',
                            'contact' => 'required|string|max:500|unique:users',

                            'agree_promotion' => 'nullable|boolean',
                        ];

                    return [
                        "social_id" => "nullable|string|max:500",
                        "social_platform" => "nullable|string|max:500",

                        'email' => 'nullable|email|max:500|unique:users',
                        'password' => 'required|string|min:8|max:500|confirmed',
                        'name' => 'required|string|max:500',
                        'contact' => 'required|string|max:500|unique:users',

                        'agree_promotion' => 'nullable|boolean',
                    ];

                case "update":
                    return [
                        'name' => 'required|string|max:500',
                        'contact' => 'required|string|max:500|unique:users,contact,'.auth()->id(),
                        "password" => "nullable|string|min:8|confirmed",
                    ];

                case "destroy":
                    return [
                        'reason' => 'nullable|string|max:3000',
                        'and_so_on' => 'nullable|string|max:3000',
                    ];

                case "updateCodeRecommend":
                    return [
                        'code_recommend' => 'required|string|max:500'
                    ];

                case "updatePassword":
                    return [
                        'password' => 'required|string|min:8|max:500',
                        'password_new' => 'required|string|min:8|max:500|confirmed'
                    ];

                case "clearPassword":
                    return [
                        'email' => 'required|string|max:500',
                        'contact' => 'required|string|max:500',
                        'password' => 'required|string|min:8|max:500|confirmed',
                    ];

                case "findId":
                    return [
                        'contact' => 'required|string|max:500',
                    ];

                case "updateAlwaysUseCouponForPackage":
                    return [
                        'always_use_coupon_for_package' => 'required|boolean'
                    ];

                case "updateAlwaysUsePointForPackage":
                    return [
                        'always_use_point_for_package' => 'required|boolean'
                    ];

                default: return [

                ];
            }
        }

    }

    public function bodyParameters()
    {
        return [
            "word" => [
                "description" => "<span class='point'>검색어</span>"
            ],

            "token" => [
                "description" => "<span class='point'>로그인 토큰 - 소셜로그인 시 필요</span>"
            ],
            "email" => [
                "description" => "<span class='point'>이메일</span>"
            ],
            "password" => [
                "description" => "<span class='point'>비밀번호</span>"
            ],
            "password_confirmation" => [
                "description" => "<span class='point'>비밀번호 확인</span>"
            ],
            "password_new" => [
                "description" => "<span class='point'>새 비밀번호</span>"
            ],
            "password_new_confirmation" => [
                "description" => "<span class='point'>새 비밀번호 확인</span>"
            ],
            "name" => [
                "description" => "<span class='point'>이름</span>"
            ],
            "contact" => [
                "description" => "<span class='point'>연락처</span>"
            ],
            "agree_promotion" => [
                "description" => "<span class='point'>프로모션 수신여부</span>"
            ],
            "reason" => [
                "description" => "<span class='point'>탈퇴사유</span>"
            ],
            "and_so_on" => [
                "description" => "<span class='point'>기타 추가입력사항</span>"
            ],
            "code_recommend" => [
                "description" => "<span class='point'>추천인 코드</span>"
            ],
        ];
    }
}
