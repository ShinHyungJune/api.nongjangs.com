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
                case 'index':
                    return [
                        'word' => ['nullable', 'string', 'max:500']
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
