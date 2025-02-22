<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    protected $userId;

    protected function prepareForValidation()
    {
        $this->userId = auth()->id();
    }

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
                        'ids' => ['nullable', 'array'],
                        'word' => ['nullable', 'string', 'max:500'],
                        'agree_promotion' => ['nullable', 'boolean'],
                        'subscribe' => ['nullable', 'boolean'],
                        'code_recommend' => ['nullable', 'integer'],
                    ];

                case 'store':
                    return [

                    ];

                case 'update':
                    return [
                        'nickname' => ['nullable', 'string', 'max:500'],
                        'birth' => ['nullable', 'date'],
                        'count_family' => ['nullable', 'integer'],
                        'refund_account' => ['nullable', 'string', 'max:500'],
                        'refund_bank' => ['nullable', 'string', 'max:500'],
                        'refund_owner' => ['nullable', 'string', 'max:500'],
                        'agree_promotion' => ['nullable', 'boolean'],
                        'active' => ['nullable', 'boolean'],
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
                        'password' => 'nullable|string|min:8|max:500|confirmed',
                        'name' => 'nullable|string|max:500',
                        'contact' => 'nullable|string|max:500|unique:users,contact,'.$this->userId,
                        'nickname' => 'nullable|string|max:500|unique:users,nickname,'.$this->userId,
                        'message' => 'nullable|string|max:500',
                        'birth' => 'nullable|date',
                        'count_family' => 'nullable|integer',
                        'agree_promotion' => 'nullable|boolean',
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
            "has_column" => [
                "description" => "<span class='point'>해당 컬럼 보유여부 (deleted_at - 탈퇴일자, 탈퇴한 사람만 보고싶을 때)</span>"
            ],
            "ids" => [
                "description" => "<span class='point'>선택된 고유번호 목록</span>"
            ],
            "subscribe" => [
                "description" => "<span class='point'>구독여부</span>"
            ],
            "active" => [
                "description" => "<span class='point'>활성여부</span>"
            ],
            "code_recommend" => [
                "description" => "<span class='point'>입력한 추천인 코드 (나를 추천한 사람 목록 보고싶다면 내 code가 code_recommend인 사람)</span>"
            ],
            "refund_account" => [
                "description" => "<span class='point'>환불계좌 번호</span>"
            ],
            "refund_bank" => [
                "description" => "<span class='point'>환불계좌 은행명</span>"
            ],
            "refund_owner" => [
                "description" => "<span class='point'>환불계좌 계좌주</span>"
            ],
            "word" => [
                "description" => "<span class='point'>검색어</span>"
            ],

            "token" => [
                "description" => "<span class='point'>로그인 토큰 - 소셜로그인 시 필요</span>"
            ],
            "email" => [
                "description" => "<span class='point'>이메일</span>"
            ],
            "contact" => [
                "description" => "<span class='point'>연락처</span>"
            ],
            "count_family" => [
                "description" => "<span class='point'>가구원수</span>"
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
            "nickname" => [
                "description" => "<span class='point'>닉네임</span>"
            ],
            "birth" => [
                "description" => "<span class='point'>생년월</span>"
            ],
            "message" => [
                "description" => "<span class='point'>프로필 메시지</span>"
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
        ];
    }
}
