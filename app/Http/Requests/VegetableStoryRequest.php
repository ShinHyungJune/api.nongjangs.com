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
                        '' => []
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
