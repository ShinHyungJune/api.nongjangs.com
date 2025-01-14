<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresetRequest extends FormRequest
{
    public function rules(): array
    {
        $method = $this->route()->getActionMethod();
        $admin = strpos($this->route()->getPrefix(), 'admin') !== false;

        if ($admin) {
            switch ($method) {
                case 'index':
                    return [
                        'word' => ['nullable', 'string', 'max:500']
                    ];

                case 'store':
                    return [
                        'order_id' => ['nullable', 'exists:orders'],
                        'cart_id' => ['nullable', 'exists:carts'],
                        'user_id' => ['required', 'exists:users'],
                        'delivery_name' => ['nullable'],
                        'delivery_contact' => ['nullable'],
                        'delivery_address' => ['nullable'],
                        'delivery_address_detail' => ['nullable'],
                        'delivery_address_zipcode' => ['nullable'],
                        'delivery_requirement' => ['nullable'],
                        'delivery_number' => ['nullable'],
                        'delivery_company' => ['required', 'integer'],
                        'delivery_at' => ['required', 'date'],
                        'price_delivery' => ['required', 'integer'],
                        'price' => ['required', 'integer'],
                        'price_total' => ['required', 'integer'],
                        'price_discount' => ['required', 'integer'],
                        'count_option_required' => ['required', 'integer'],
                        'count_option_additional' => ['required', 'integer'],
                    ];

                case 'update':
                    return [
                        'order_id' => ['nullable', 'exists:orders'],
                        'cart_id' => ['nullable', 'exists:carts'],
                        'user_id' => ['required', 'exists:users'],
                        'delivery_name' => ['nullable'],
                        'delivery_contact' => ['nullable'],
                        'delivery_address' => ['nullable'],
                        'delivery_address_detail' => ['nullable'],
                        'delivery_address_zipcode' => ['nullable'],
                        'delivery_requirement' => ['nullable'],
                        'delivery_number' => ['nullable'],
                        'delivery_company' => ['required', 'integer'],
                        'delivery_at' => ['required', 'date'],
                        'price_delivery' => ['required', 'integer'],
                        'price' => ['required', 'integer'],
                        'price_total' => ['required', 'integer'],
                        'price_discount' => ['required', 'integer'],
                        'count_option_required' => ['required', 'integer'],
                        'count_option_additional' => ['required', 'integer'],//
                    ];

                case 'destroy':
                    return [
                        'ids' => ['required', 'array'],
                    ];

                default:
                    return [];
            }
        } else {
            switch ($method) {
                case 'index':
                    return [
                        '' => []
                    ];

                case 'store':
                    return [
                        'options' => 'required|array',
                        'options.option_id' => ['required', 'integer'],
                        'options.count' => ['required', 'integer', 'min:1'],
                    ];

                case 'update':
                    return [
                        '' => []
                    ];


                default:
                    return [];
            }
        }
    }

    public function bodyParameters()
    {
        return [
            // 이 모델만 쓰이는 애들
            'option_id' => [
                'description' => '<span class="point">옵션 고유번호</span>',
            ],
            'count' => [
                'description' => '<span class="point">개수</span>',
            ],

            // 늘 쓰이는 애들
            'word' => [
                'description' => '<span class="point">검색어</span>',
            ],
            'ids' => [
                'description' => '<span class="point">선택한 대상들의 고유번호 목록</span>',
                // 'example' => '',
            ],
            'files' => [
                'description' => '<span class="point">이미지 파일 목록 input-images(multiple=false) 사용</span>',
                // 'example' => '',
            ],
            'files_remove_ids' => [
                'description' => '<span class="point">삭제할 미디어 파일 대상들의 고유번호 목록</span>',
                // 'example' => '',
            ],
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
