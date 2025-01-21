<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageSettingRequest extends FormRequest
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
                        'user_id' => ['required', 'exists:users'],
                        'card_id' => ['required', 'exists:cards'],
                        'delivery_id' => ['required', 'exists:deliveries'],
                        'type_package' => ['required'],
                        'term_week' => ['required', 'integer'],
                        'active' => ['boolean'],
                        'will_order_at' => ['nullable', 'date'],
                        'first_package_id' => ['nullable', 'exists:packages'],
                        'retry' => ['required', 'integer'],
                    ];

                case 'update':
                    return [
                        'user_id' => ['required', 'exists:users'],
                        'card_id' => ['required', 'exists:cards'],
                        'delivery_id' => ['required', 'exists:deliveries'],
                        'type_package' => ['required'],
                        'term_week' => ['required', 'integer'],
                        'active' => ['boolean'],
                        'will_order_at' => ['nullable', 'date'],
                        'first_package_id' => ['nullable', 'exists:packages'],
                        'retry' => ['required', 'integer'],//
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
                        'card_id' => ['nullable', 'integer'],
                        'delivery_id' => ['required', 'integer'],
                        'type_package' => ['required', 'integer'],
                        'term_week' => ['required', 'integer'],
                        'unlike_material_ids' => ['nullable', 'array'],
                        'active' => ['required', 'boolean'],
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
            'card_id' => [
                'description' => '<span class="point">결제카드 고유번호</span>',
            ],
            'delivery_id' => [
                'description' => '<span class="point">배송지 고유번호</span>',
            ],
            'type_package' => [
                'description' => '<span class="point">꾸러미 유형</span>',
            ],
            'term_week' => [
                'description' => '<span class="point">배송주기</span>',
            ],
            'unlike_material_ids' => [
                'description' => '<span class="point">비선호 품목 고유번호 목록</span>',
            ],
            'active' => [
                'description' => '<span class="point">활성여부 (1 - 정기구독일 때 | 0 - 1회성 구매일 때)</span>',
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
