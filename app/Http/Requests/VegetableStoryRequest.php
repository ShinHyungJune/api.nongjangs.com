<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VegetableStoryRequest extends FormRequest
{
    public function rules(): array
    {
        $method = $this->route()->getActionMethod();
        $admin = strpos($this->route()->getPrefix(), 'admin') !== false;

        if ($admin) {
            switch ($method) {
                case 'index':
                    return [
                        'user_id' => ['nullable', 'integer'],
                        'has_column' => ['nullable', 'string', 'max:500'],
                        'word' => ['nullable', 'string', 'max:500']
                    ];

                case 'store':
                    return [
                        'user_id' => ['required', 'exists:users'],
                        'package_id' => ['nullable', 'exists:packages'],
                        'product_id' => ['nullable', 'exists:products'],
                        'preset_product_id' => ['nullable', 'exists:preset_product'],
                        'recipe_id' => ['nullable', 'exists:recipes'],
                        'description' => ['nullable'],
                    ];

                case 'update':
                    return [
                        'user_id' => ['required', 'exists:users'],
                        'package_id' => ['nullable', 'exists:packages'],
                        'product_id' => ['nullable', 'exists:products'],
                        'preset_product_id' => ['nullable', 'exists:preset_product'],
                        'recipe_id' => ['nullable', 'exists:recipes'],
                        'description' => ['nullable'],//
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
                        'order_by' => ['nullable', 'string', 'max:500'],
                        'user_id' => ['nullable', 'integer'],
                        'tag_ids' => ['nullable', 'array'],
                        'word' => ['nullable', 'string', 'max:500'],
                        'has_column' => ['nullable', 'string', 'max:500'],
                        'package_id' => ['nullable', 'integer'],
                        'recipe_id' => ['nullable', 'integer'],
                    ];

                case 'store':
                    return [
                        'imgs' => ['required', 'array', 'min:1'],
                        'preset_product_id' => ['nullable', 'integer'],
                        'description' => ['required', 'string', 'max:15000'],
                        'recipe_id' => ['nullable', 'integer'],
                        'tag_ids' => ['nullable', 'array'],
                    ];

                case 'update':
                    return [
                        'imgs' => ['required', 'array', 'min:1'],
                        'description' => ['required', 'string', 'max:15000'],
                        'recipe_id' => ['nullable', 'integer'],
                        'tag_ids' => ['nullable', 'array'],
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
            'order_by' => [
                'description' => '<span class="point">정렬 (count_like - 좋아요순 | created_at - 등록순)</span>',
            ],
            'user_id' => [
                'description' => '<span class="point">사용자 고유번호</span>',
            ],
            'imgs' => [
                'description' => '<span class="point">이미지 목록</span>',
            ],
            'preset_product_id' => [
                'description' => '<span class="point">출고상품 고유번호</span>',
            ],
            'description' => [
                'description' => '<span class="point">내용</span>',
            ],
            'recipe_id' => [
                'description' => '<span class="point">레시피 고유번호</span>',
            ],
            'tag_ids' => [
                'description' => '<span class="point">태그 고유번호 목록</span>',
            ],
            'has_column' => [
                'description' => '<span class="point">특정 컬럼 보유여부 (package_id - 꾸러미 관련만 보기 | product_id - 직거래상품 관련만 보기)</span>',
            ],
            'package_id' => [
                'description' => '<span class="point">패키지 고유번호</span>',
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
