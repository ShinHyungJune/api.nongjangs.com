<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
                        'state' => ['required', 'integer'],
                        'category_id' => ['required', 'exists:categories'],
                        'farm_id' => ['required', 'exists:farms'],
                        'city_id' => ['required', 'exists:cities'],
                        'county_id' => ['required', 'exists:counties'],
                        'uuid' => ['required'],
                        'title' => ['required'],
                        'price' => ['required', 'integer'],
                        'price_origin' => ['required', 'integer'],
                        'need_tax' => ['boolean'],
                        'can_use_coupon' => ['boolean'],
                        'can_use_point' => ['boolean'],
                        'count' => ['required', 'integer'],
                        'type_delivery' => ['required', 'integer'],
                        'delivery_company' => ['required', 'integer'],
                        'type_delivery_price' => ['required', 'integer'],
                        'price_delivery' => ['required', 'integer'],
                        'prices_delivery' => ['nullable'],
                        'min_price_for_free_delivery_price' => ['required', 'integer'],
                        'can_delivery_far_place' => ['boolean'],
                        'delivery_price_far_place' => ['required', 'integer'],
                        'delivery_company_refund' => ['required', 'integer'],
                        'delivery_price_refund' => ['required', 'integer'],
                        'delivery_address_refund' => ['nullable'],
                        'description' => ['required'],
                    ];

                case 'update':
                    return [
                        'state' => ['required', 'integer'],
                        'category_id' => ['required', 'exists:categories'],
                        'farm_id' => ['required', 'exists:farms'],
                        'city_id' => ['required', 'exists:cities'],
                        'county_id' => ['required', 'exists:counties'],
                        'uuid' => ['required'],
                        'title' => ['required'],
                        'price' => ['required', 'integer'],
                        'price_origin' => ['required', 'integer'],
                        'need_tax' => ['boolean'],
                        'can_use_coupon' => ['boolean'],
                        'can_use_point' => ['boolean'],
                        'count' => ['required', 'integer'],
                        'type_delivery' => ['required', 'integer'],
                        'delivery_company' => ['required', 'integer'],
                        'type_delivery_price' => ['required', 'integer'],
                        'price_delivery' => ['required', 'integer'],
                        'prices_delivery' => ['nullable'],
                        'min_price_for_free_delivery_price' => ['required', 'integer'],
                        'can_delivery_far_place' => ['boolean'],
                        'delivery_price_far_place' => ['required', 'integer'],
                        'delivery_company_refund' => ['required', 'integer'],
                        'delivery_price_refund' => ['required', 'integer'],
                        'delivery_address_refund' => ['nullable'],
                        'description' => ['required'],//
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
                        '' => []
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
            'example' => [
                'description' => '<span class="point"></span>',
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
