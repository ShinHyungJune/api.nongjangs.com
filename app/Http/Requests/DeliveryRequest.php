<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryRequest extends FormRequest
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
            case "store":
                return [
                    'main' => ['required', 'boolean'],
                    'name' => ['required', 'string', 'max:500'],
                    'contact' => ['required', 'string', 'max:500'],
                    'address' => ['required', 'string', 'max:500'],
                    'address_detail' => ['required', 'string', 'max:500'],
                    'address_zipcode' => ['required', 'string', 'max:500'],
                ];
            case "update":
                return [
                    'main' => ['required', 'boolean'],
                    'name' => ['required', 'string', 'max:500'],
                    'contact' => ['required', 'string', 'max:500'],
                    'address' => ['required', 'string', 'max:500'],
                    'address_detail' => ['required', 'string', 'max:500'],
                    'address_zipcode' => ['required', 'string', 'max:500'],
                ];
            default:
                return [];
        }

    }

    public function bodyParameters()
    {
        return [
            'main' => [
                'description' => '<span class="point">메인 배송지 여부</span>'
            ],
            'name' => [
                'description' => '<span class="point">이름</span>'
            ],
            'contact' => [
                'description' => '<span class="point">연락처</span>'
            ],
            'address' => [
                'description' => '<span class="point">주소</span>'
            ],
            'address_detail' => [
                'description' => '<span class="point">상세주소</span>'
            ],
            'address_zipcode' => [
                'description' => '<span class="point">우편번호</span>'
            ],

        ];
    }
}
