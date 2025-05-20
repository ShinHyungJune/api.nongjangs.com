<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliverySettingRequest extends FormRequest
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
                        'type_delivery' => ['required', 'integer'],
                        'delivery_company' => ['required', 'integer'],
                        'type_delivery_price' => ['required', 'integer'],
                        'price_delivery' => ['required', 'integer'],
                        'prices_delivery' => ['nullable'],
                        'min_price_for_free_delivery_price' => ['nullable', 'integer'],
                        'can_delivery_far_place' => ['boolean'],
                        // 'delivery_price_far_place' => ['nullable', 'integer'],
                        'ranges_far_place' => ['nullable', 'array'],
                    ];

                case 'update':
                    return [
                        'type_delivery' => ['required', 'integer'],
                        'delivery_company' => ['required', 'integer'],
                        'type_delivery_price' => ['required', 'integer'],
                        'price_delivery' => ['required', 'integer'],
                        'prices_delivery' => ['nullable'],
                        'min_price_for_free_delivery_price' => ['nullable', 'integer'],
                        'can_delivery_far_place' => ['boolean'],
                        // 'delivery_price_far_place' => ['nullable', 'integer'],//
                        'ranges_far_place' => ['nullable', 'array'],//
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
            'type_delivery' => [
                'description' => '<span class="point">배송유형 (1 - FREE 무료배송 | 2 - EACH 개별배송)</span>',
            ],
            'delivery_company' => [
                'description' => '<span class="point">택배사 (1 - CJ CJ | 2 - POST 우체국 | 3 - HANJIN 한진)</span>',
            ],
            'type_delivery_price' => [
                'description' => '<span class="point">배송비 유형 (1 - STATIC 고정배송비 | 2 - CONDITIONAL 조건 무료배송 | 3 - PRICE_BY_COUNT 수량별 차등 배송비)</span>',
            ],
            'price_delivery' => [
                'description' => '<span class="point">배송비</span>',
            ],
            'prices_delivery' => [
                'description' => '<span class="point">수량별 차등 배송비 목록 [{count: 구매수량, price: 배송비}]</span>',
            ],
            'min_price_for_free_delivery_price' => [
                'description' => '<span class="point">무료배송 최소주문금액</span>',
            ],
            'can_delivery_far_place' => [
                'description' => '<span class="point">제주/도서산간 배송 가능여부</span>',
            ],
            /*'delivery_price_far_place' => [
                'description' => '<span class="point">제주/도서산간 배송 추가배송비</span>',
            ],*/
            'ranges_far_place' => [
                'description' => '<span class="point">제주/도서산간 지역 설정 (["title" => "", "zipcode_start" => "", "zipcode_end" => "", "price" => ""]</span>',
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
