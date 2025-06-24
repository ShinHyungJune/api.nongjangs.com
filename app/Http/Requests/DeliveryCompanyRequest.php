<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryCompanyRequest extends FormRequest
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
                        'title' => ['required', 'string', 'max:255'],
                        'code' => ['required', 'string', 'max:50', 'unique:delivery_companies,code'],
                    ];

                case 'update':
                    return [
                        'title' => ['required', 'string', 'max:255'],
                        'code' => ['required', 'string', 'max:50', 'unique:delivery_companies,code,'.$this->route('deliveryCompany')->id],
                    ];

                case 'destroy':
                    return [
                        'ids' => ['required', 'array'],
                    ];

                default:
                    return [];
            }
        } else {
            switch ($method){
                case 'index':
                    return [
                        'word' => ['nullable', 'string', 'max:500']
                    ];

                default:
                    return [];
            }
        }
    }

    /**
     * Get the body parameters documentation.
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'title' => [
                'description' => '<span class="point">배송사 이름</span>',
            ],
            'code' => [
                'description' => '<span class="point">배송사 코드</span>',
            ],
            'ids' => [
                'description' => '<span class="point">선택한 대상들의 고유번호 목록</span>',
            ],
        ];
    }
}