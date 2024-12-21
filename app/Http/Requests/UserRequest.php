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

                            'type' => 'required|integer',
                            'email' => 'nullable|email|max:500',
                            'name' => 'nullable|string|max:500',
                            'contact' => 'required|string|max:500|unique:users',
                            'address' => 'nullable|string|max:500',
                            'address_detail' => 'nullable|string|max:500',
                            'address_zipcode' => 'nullable|string|max:500',

                            'agree_promotion_sms' => 'nullable|boolean',

                            'business_number' => 'nullable|string|max:500',
                            'company_title' => 'nullable|string|max:500',
                            'company_president' => 'nullable|string|max:500',
                            'company_size' => 'nullable|string|max:500',
                            'company_type' => 'nullable|string|max:500',
                            'company_category' => 'nullable|string|max:500',
                        ];

                    return [
                        "social_id" => "nullable|string|max:500",
                        "social_platform" => "nullable|string|max:500",

                        'type' => 'required|integer',
                        'ids' => 'required|string|max:500|unique:users',
                        'email' => 'nullable|email|max:500|unique:users',
                        'password' => 'required|string|min:8|max:500|confirmed',
                        'name' => 'required|string|max:500',
                        'contact' => 'required|string|max:500|unique:users',
                        'address' => 'required|string|max:500',
                        'address_detail' => 'required|string|max:500',
                        'address_zipcode' => 'required|string|max:500',

                        'agree_promotion_sms' => 'nullable|boolean',

                        'business_number' => 'nullable|string|max:500',
                        'company_title' => 'nullable|string|max:500',
                        'company_president' => 'nullable|string|max:500',
                        'company_size' => 'nullable|string|max:500',
                        'company_type' => 'nullable|string|max:500',
                        'company_category' => 'nullable|string|max:500',
                    ];

                case "update":
                    return [
                        'name' => 'required|string|max:500',
                        'address' => 'required|string|max:500',
                        'address_detail' => 'required|string|max:500',
                        'address_zipcode' => 'required|string|max:500',
                        'contact' => 'required|string|max:500|unique:users,contact,'.auth()->id(),
                        "password" => "nullable|string|min:8|confirmed",
                    ];

                case "destroy":
                    return [
                        'reason' => 'nullable|string|max:3000',
                        'and_so_on' => 'nullable|string|max:3000',
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
            "ids" => [
                "description" => "<span class='point'>아이디</span>"
            ],
            "type" => [
                "description" => "<span class='point'>유형 (1 - 일반 COMMON | 2 - 사업자 COMPANY)</span>"
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
            "name" => [
                "description" => "<span class='point'>이름</span>"
            ],
            "contact" => [
                "description" => "<span class='point'>연락처</span>"
            ],
            "address" => [
                "description" => "<span class='point'>주소</span>"
            ],
            "address_detail" => [
                "description" => "<span class='point'>상세주소</span>"
            ],
            "address_zipcode" => [
                "description" => "<span class='point'>우편번호</span>"
            ],
            "business_number" => [
                "description" => "<span class='point'>사업자번호</span>"
            ],
            "company_title" => [
                "description" => "<span class='point'>회사명</span>"
            ],
            "company_president" => [
                "description" => "<span class='point'>대표자명</span>"
            ],
            "company_size" => [
                "description" => "<span class='point'>업체규모</span>"
            ],
            "company_type" => [
                "description" => "<span class='point'>업종</span>"
            ],
            "company_category" => [
                "description" => "<span class='point'>업태</span>"
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
