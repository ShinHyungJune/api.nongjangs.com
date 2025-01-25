<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeRequest extends FormRequest
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
                        'title' => ['required'],
                        'description' => ['nullable'],
                        'materials' => ['nullable'],
                        'seasonings' => ['nullable'],
                        'ways' => ['nullable'],
                    ];

                case 'update':
                    return [
                        'user_id' => ['required', 'exists:users'],
                        'title' => ['required'],
                        'description' => ['nullable'],
                        'materials' => ['nullable'],
                        'seasonings' => ['nullable'],
                        'ways' => ['nullable'],//
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
                        'word' => ['nullable', 'string', 'max:500'],
                        'tag_ids' => ['nullable', 'array'],
                        'order_by' => ['nullable', 'string', 'max:500'],
                        'package_id' => ['nullable', 'integer'],
                        'is_bookmark' => ['nullable', 'boolean'],
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
            'tag_ids' => [
                'description' => '<span class="point">태그 고유번호 목록</span>',
            ],
            'order_by' => [
                'description' => '<span class="point">정렬 (count_like - 좋아요순 | created_at - 등록순)</span>',
            ],
            'package_id' => [
                'description' => '<span class="point">꾸러미 고유번호 (이번주 꾸러미의 레시피 보고싶을 때 이번주 꾸러미의 고유번호 보내기)</span>',
            ],
            'is_bookmark' => [
                'description' => '<span class="point">북마크 여부 (1 - 내가 북마크한것만 보기)</span>',
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
