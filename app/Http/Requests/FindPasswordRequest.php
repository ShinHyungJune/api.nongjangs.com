<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FindPasswordRequest extends FormRequest
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

        switch ($method){
            case 'store':
                return [
                    "ids" => "required|string|max:500",
                    "contact" => "required|string|max:500",
                    "password" => "required|string|min:8|max:500|confirmed",
                ];

            default:
                return [];
        }
    }

    public function bodyParameters()
    {
        return [
            'ids' => [
                'description' => '<span class="point">아이디</span>',
                // 'example' => '',
            ],
            'contact' => [
                'description' => '<span class="point">연락처</span>',
                // 'example' => '',
            ],
            'password' => [
                'description' => '<span class="point">비밀번호</span>',
                // 'example' => '',
            ],
            'password_confirmation' => [
                'description' => '<span class="point">비밀번호 확인</span>',
                // 'example' => '',
            ],
        ];
    }
}
