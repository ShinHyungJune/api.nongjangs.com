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
                        'title' => ['required'],
                        'moment' => ['required', 'integer'],
                        'type' => ['required', 'integer'],
                        'type_package' => ['nullable', 'integer'],
                        'all_product' => ['nullable', 'boolean'],
                        'target' => ['required', 'integer'],
                        'grade_id' => ['nullable', 'exists:grades'],
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
                        'title' => ['required'],
                        'moment' => ['required', 'integer'],
                        'type' => ['required', 'integer'],
                        'type_package' => ['nullable', 'integer'],
                        'all_product' => ['nullable', 'boolean'],
                        'target' => ['required', 'integer'],
                        'grade_id' => ['nullable', 'exists:grades'],
                        'min_order' => ['nullable', 'integer'],
                        'type_discount' => ['required', 'integer'],
                        'value' => ['required', 'integer'],
                        'max_price_discount' => ['required', 'integer'],
                        'min_price_order' => ['required', 'integer'],
                        'type_expire' => ['required', 'integer'],
                        'started_at' => ['nullable', 'date'],
                        'finished_at' => ['nullable', 'date'],
                        'days' => ['nullable', 'integer'],//
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
            'product_id' => [
                'description' => '<span class="point">상품 고유번호</span>',
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
