<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponGroupRequest extends FormRequest
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
                        'products' => ['nullable', 'array'],
                        'title' => ['required'],
                        'moment' => ['nullable', 'integer'],
                        'type' => ['required', 'integer'],
                        'type_package' => ['nullable', 'integer'],
                        'all_product' => ['nullable', 'boolean'],
                        'target' => ['required', 'integer'],
                        'grade_id' => ['nullable', 'integer'],
                        'min_order' => ['nullable', 'integer'],
                        'type_discount' => ['required', 'integer'],
                        'value' => ['required', 'integer'],
                        'max_price_discount' => ['required', 'integer'],
                        'min_price_order' => ['required', 'integer'],
                        'type_expire' => ['required', 'integer'],
                        'started_at' => ['nullable', 'date'],
                        'finished_at' => ['nullable', 'date'],
                        'days' => ['nullable', 'integer'],
                    ];

                case 'update':
                    return [
                        'products' => ['nullable', 'array'],
                        'title' => ['required'],
                        'moment' => ['nullable', 'integer'],
                        'type' => ['required', 'integer'],
                        'type_package' => ['nullable', 'integer'],
                        'all_product' => ['nullable', 'boolean'],
                        'target' => ['required', 'integer'],
                        'grade_id' => ['nullable', 'integer'],
                        'min_order' => ['nullable', 'integer'],
                        'type_discount' => ['required', 'integer'],
                        'value' => ['required', 'integer'],
                        'max_price_discount' => ['required', 'integer'],
                        'min_price_order' => ['required', 'integer'],
                        'type_expire' => ['required', 'integer'],
                        'started_at' => ['nullable', 'date'],
                        'finished_at' => ['nullable', 'date'],
                        'days' => ['nullable', 'integer'],
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
                        'product_id' => ['nullable', 'integer'],
                        'order_by' => ['nullable', 'string', 'max:500'],
                        'can_download' => ['nullable', 'integer'],
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
            'title' => [
                'description' => '<span class="point">제목</span>',
            ],
            'moment' => [
                'description' => '<span class="point">특정 시점에 발급되는 쿠폰 (1 - UPDATE_PROFILE 프로필 업데이트 | 2 - GRADE 등급혜택 | 3 - BIRTHDAY 생일쿠폰 | 4 - FIRST_ORDER 첫구매)</span>',
            ],
            'type' => [
                'description' => '<span class="point">꾸러미 유형 (1 - SINGLE 싱글 | 2 - BUNGLE 벙글</span>',
            ],
            'all_product' => [
                'description' => '<span class="point">모든 상품 해당여부</span>',
            ],
            'target' => [
                'description' => '<span class="point">발급대상 (1 - ALL 전체회원 | 2 - GRADE 고객등급 | 3 - ORDER_HISTORY 구매이력 | 4 - PERSONAL 개인회원)</span>',
            ],
            'min_order' => [
                'description' => '<span class="point">최소 구매이력수 (최근 3개월 n회 구매한 사람에게만 적용하는 쿠폰)</span>',
            ],
            'type_discount' => [
                'description' => '<span class="point">할인유형 (1 - NUMBER 원 | 2 - RATIO %)</span>',
            ],
            'value' => [
                'description' => '<span class="point">할인값</span>',
            ],
            'max_price_discount' => [
                'description' => '<span class="point">최대할인금액</span>',
            ],
            'min_price_order' => [
                'description' => '<span class="point">최소주문금액</span>',
            ],
            'type_expire' => [
                'description' => '<span class="point">만료유형 (1 - SPECIFIC 특정기간 | 2 - FROM_DOWNLOAD 다운로드일 기준</span>',
            ],
            'started_at' => [
                'description' => '<span class="point">시작일자</span>',
            ],
            'finished_at' => [
                'description' => '<span class="point">종료일자</span>',
            ],
            'days' => [
                'description' => '<span class="point">유효기간(다운로드일 기준)</span>',
            ],

            'grade_id' => [
                'description' => '<span class="point">등급 고유번호</span>',
            ],
            'products' => [
                'description' => '<span class="point">상품 목록</span>',
            ],
            'order_by' => [
                'description' => '<span class="point">value - 할인값 | created_at - 등록순</span>',
            ],
            'can_download' => [
                'description' => '<span class="point">다운가능여부</span>',
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
