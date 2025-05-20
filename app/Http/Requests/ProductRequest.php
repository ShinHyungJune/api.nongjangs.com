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
                        'category_id' => ['required', 'integer'],
                        'files' => ['required'],
                        'title' => ['required'],
                        'farm_id' => ['required', 'integer'],
                        'county_id' => ['required', 'integer'],
                        'tags' => ['nullable', 'array'],
                        'price' => ['required', 'integer'],
                        'price_origin' => ['required', 'integer'],
                        'need_tax' => ['boolean'],
                        'can_use_coupon' => ['boolean'],
                        'can_use_point' => ['boolean'],
                        'count' => ['required', 'integer'],
                        'requiredOptions' => ['nullable', 'array'],
                        'additionalOptions' => ['nullable', 'array'],
                        'type_delivery' => ['required', 'integer'],
                        'delivery_company' => ['required', 'integer'],
                        'type_delivery_price' => ['required', 'integer'],
                        'price_delivery' => ['required', 'integer'],
                        'prices_delivery' => ['nullable'],
                        'min_price_for_free_delivery_price' => ['required', 'integer'],
                        'can_delivery_far_place' => ['boolean'],
                        'delivery_prices_far_place' => ['nullable', 'array'],
                        'delivery_company_refund' => ['required', 'integer'],
                        'delivery_price_refund' => ['required', 'integer'],
                        'delivery_address_refund' => ['nullable'],
                        'description' => ['required'],
                    ];

                case 'update':
                    return [
                        'state' => ['required', 'integer'],
                        'category_id' => ['required', 'integer'],
                        'files' => ['nullable'],
                        'title' => ['required'],
                        'farm_id' => ['required', 'integer'],
                        'county_id' => ['required', 'integer'],
                        'tags' => ['nullable', 'array'],
                        'price' => ['required', 'integer'],
                        'price_origin' => ['required', 'integer'],
                        'need_tax' => ['boolean'],
                        'can_use_coupon' => ['boolean'],
                        'can_use_point' => ['boolean'],
                        'count' => ['required', 'integer'],
                        'requiredOptions' => ['nullable', 'array'],
                        'additionalOptions' => ['nullable', 'array'],
                        'type_delivery' => ['required', 'integer'],
                        'delivery_company' => ['required', 'integer'],
                        'type_delivery_price' => ['required', 'integer'],
                        'price_delivery' => ['required', 'integer'],
                        'prices_delivery' => ['nullable'],
                        'min_price_for_free_delivery_price' => ['required', 'integer'],
                        'can_delivery_far_place' => ['boolean'],
                        'delivery_prices_far_place' => ['nullable', 'array'],
                        'delivery_company_refund' => ['required', 'integer'],
                        'delivery_price_refund' => ['required', 'integer'],
                        'delivery_address_refund' => ['nullable'],
                        'description' => ['required'],
                    ];

                case 'destroy':
                    return [
                        'ids' => ['required', 'array'],
                    ];

                case 'updateState':
                    return [
                        'state' => ['required', 'integer'],
                    ];

                default:
                    return [];
            }
        } else {
            switch ($method) {
                case 'index':
                    return [
                        'word' => ['nullable', 'string', 'max:500'],
                        'order_by' => ['nullable', 'string', 'max:500'],
                        'tag_ids' => ['nullable', 'array', 'max:20'],
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
            'word' => [
                'description' => '<span class="point">검색어</span>',
            ],
            'order_by' => [
                'description' => '<span class="point">정렬기준 (created_at 생성순 | count_review 리뷰순 | count_order 주문수순)</span>',
            ],
            'state' => [
                'description' => '<span class="point">상태 (1 - ONGOING 진행중 | HIDE - 2 숨김 | EMPTY - 3 품절)</span>',
            ],
            'category_id' => [
                'description' => '<span class="point">소분류 고유번호</span>',
            ],
            'title' => [
                'description' => '<span class="point">제목</span>',
            ],
            'farm_id' => [
                'description' => '<span class="point">농가 고유번호</span>',
            ],
            'county_id' => [
                'description' => '<span class="point">시군구 고유번호</span>',
            ],
            'tag_ids' => [
                'description' => '<span class="point">태그 고유번호 목록</span>',
            ],
            'price' => [
                'description' => '<span class="point">판매가</span>',
            ],
            'price_origin' => [
                'description' => '<span class="point">정가</span>',
            ],
            'need_tax' => [
                'description' => '<span class="point">과세여부 (1 - 과세 | 0 - 면세)</span>',
            ],
            'can_use_coupon' => [
                'description' => '<span class="point">쿠폰사용 가능여부</span>',
            ],
            'can_use_point' => [
                'description' => '<span class="point">적립금 사용 가능여부</span>',
            ],
            'count' => [
                'description' => '<span class="point">재고수</span>',
            ],
            'requiredOptions' => [
                'description' => '<span class="point">필수옵션 목록 ([{title:옵션명, price: 옵션가격, count: 재고수량, state: 판매상태}])</span>',
            ],
            'additionalOptions' => [
                'description' => '<span class="point">추가옵션 목록 ([{title:옵션명, price: 옵션가격, count: 재고수량, state: 판매상태}])</span>',
            ],
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
            'ranges_far_place' => [
                'description' => '<span class="point">제주/도서산간 지역 설정 (["title" => "", "zipcode_start" => "", "zipcode_end" => "", "price" => ""]</span>',
            ],
            'delivery_company_refund' => [
                'description' => '<span class="point">반품 택배사 (1 - CJ CJ | 2 - POST 우체국 | 3 - HANJIN 한진)</span>',
            ],
            'delivery_price_refund' => [
                'description' => '<span class="point">반품 배송비</span>',
            ],
            'delivery_address_refund' => [
                'description' => '<span class="point">교환/반품 배송지</span>',
            ],
            'description' => [
                'description' => '<span class="point">상품 상세</span>',
            ],
            'tags' => [
                'description' => '<span class="point">태그 목록 [{id:고유번호}]</span>',
            ],

            // 늘 쓰이는 애들
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
