<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresetProductRequest extends FormRequest
{
    public function rules(): array
    {
        $method = $this->route()->getActionMethod();
        $admin = strpos($this->route()->getPrefix(), 'admin') !== false;

        if ($admin) {
            switch ($method) {
                case 'import':
                    return [
                        'file' => ['required'],
                    ];

                case 'export':
                    return [
                        'word' => ['nullable', 'string', 'max:500'],
                        'has_column' => ['nullable', 'string', 'max:500'],
                        'user_id' => ['nullable', 'integer'],
                        'type_package' => ['nullable', 'integer'],
                        'state' => ['nullable', 'integer'],
                        'states' => ['nullable', 'array'],

                        'package_id' => ['nullable', 'integer'],
                        'started_at' => ['nullable', 'date'],
                        'finished_at' => ['nullable', 'date'],
                    ];

                case 'index':
                    return [
                        'word' => ['nullable', 'string', 'max:500'],
                        'has_column' => ['nullable', 'string', 'max:500'],
                        'user_id' => ['nullable', 'integer'],
                        'type_package' => ['nullable', 'integer'],
                        'state' => ['nullable', 'integer'],
                        'states' => ['nullable', 'array'],

                        'package_id' => ['nullable', 'integer'],
                        'started_at' => ['nullable', 'date'],
                        'finished_at' => ['nullable', 'date'],
                    ];

                case 'store':
                    return [
                        'state' => ['required', 'integer'],
                        'preset_id' => ['required', 'exists:presets'],
                        'product_id' => ['required', 'exists:products'],
                        'option_id' => ['required', 'exists:options'],
                        'coupon_id' => ['required', 'exists:coupons'],
                        'product_title' => ['required'],
                        'product_price' => ['required', 'integer'],
                        'product_price_origin' => ['required', 'integer'],
                        'count' => ['required', 'integer'],
                        'option_title' => ['required'],
                        'option_price' => ['required', 'integer'],
                        'option_type' => ['required', 'integer'],
                    ];

                case 'update':
                    return [
                        'state' => ['required', 'integer'],
                        'preset_id' => ['required', 'exists:presets'],
                        'product_id' => ['required', 'exists:products'],
                        'option_id' => ['required', 'exists:options'],
                        'coupon_id' => ['required', 'exists:coupons'],
                        'product_title' => ['required'],
                        'product_price' => ['required', 'integer'],
                        'product_price_origin' => ['required', 'integer'],
                        'count' => ['required', 'integer'],
                        'option_title' => ['required'],
                        'option_price' => ['required', 'integer'],
                        'option_type' => ['required', 'integer'],//
                    ];

                case 'updateDeliveryAddress':
                    return [
                        'delivery_address' => ['required', 'string', 'max:500'],
                        'delivery_address_detail' => ['required', 'string', 'max:500'],
                        'delivery_address_zipcode' => ['required', 'string', 'max:500'],
                    ];

                case 'updateState':
                    return [
                        'state' => ['required', 'integer'],
                        'reason_deny_cancel' => ['nullable', 'string', 'max:500'],
                    ];

                case 'willOut':
                    return [
                        'ids' => ['required', 'array'],
                    ];

                case 'materials':
                    return [
                        'package_id' => ['required', 'integer'],
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
                        'can_review' => ['nullable', 'boolean'],
                        'can_vegetable_story' => ['nullable', 'boolean']
                    ];

                case 'store':
                    return [
                        '' => []
                    ];

                case 'updateCoupon':
                    return [
                        'coupon_id' => ['required', 'integer']
                    ];

                case 'requestCancel':
                    return [
                        'reason_request_cancel' => 'required|string|max:5000'
                    ];

                case 'updateMaterials':
                    return [
                        'materials' => 'required|array',
                        'materials.*.id' => 'required|integer',
                        'materials.*.count' => 'required|integer|min:1',
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
            'has_column' => [
                'description' => '<span class="point">해당 컬럼데이터 보유여부 (package_id - 꾸러미 상품 | product_id - 직거래상품)</span>',
            ],
            'type_package' => [
                'description' => '<span class="point">꾸러미 유형(1 - SINGLE 싱글 | 2 - BUNGLE 벙글)</span>',
            ],
            'user_id' => [
                'description' => '<span class="point">사용자 고유번호</span>',
            ],
            'state' => [
                'description' => '<span class="point">상태 (StatePresetProduct)</span>',
            ],
            'states' => [
                'description' => '<span class="point">상태 목록 (StatePresetProduct 목록)</span>',
            ],
            'can_review' => [
                'description' => '<span class="point">후기 작성가능여부</span>',
            ],
            'can_vegetable_story' => [
                'description' => '<span class="point">채소이야기 작성가능여부</span>',
            ],
            'materials' => [
                'description' => '<span class="point">품목구성 ([{id: material의 고유번호, count: 개수}])</span>',
            ],
            'reason_request_cancel' => [
                'description' => '<span class="point">취소요청사유</span>',
            ],
            'coupon_id' => [
                'description' => '<span class="point">쿠폰고유번호</span>',
            ],
            'package_id' => [
                'description' => '<span class="point">회차 고유번호</span>',
            ],
            'started_at' => [
                'description' => '<span class="point">시작일자</span>',
            ],
            'finished_at' => [
                'description' => '<span class="point">종료일자</span>',
            ],
            'align' => [
                'description' => '<span class="point">정렬 (desc - 내림차순 | asc - 오름차순)</span>',
            ],
            'order_by' => [
                'description' => '<span class="point">정렬기준 (created_at - 날짜순)</span>',
            ],
            'reason_deny_cancel' => [
                'description' => '<span class="point">요청반려사유</span>',
            ],

            // 늘 쓰이는 애들
            'word' => [
                'description' => '<span class="point">검색어</span>',
            ],
            'ids' => [
                'description' => '<span class="point">선택한 대상들의 고유번호 목록</span>',
                // 'example' => '',
            ],
            'file' => [
                'description' => '<span class="point">파일 객체</span>',
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
