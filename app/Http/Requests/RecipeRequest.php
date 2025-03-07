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
                        'title' => ['required'],
                        'description' => ['nullable', 'string', 'max:5000'],
                        'materials' => ['nullable', 'array'],
                        'seasonings' => ['nullable', 'array'],
                        'ways' => ['nullable', 'array'],
                        'tags' => ['nullable', 'array'],
                    ];

                case 'update':
                    return [
                        'title' => ['required'],
                        'description' => ['nullable', 'string', 'max:5000'],
                        'materials' => ['nullable', 'array'],
                        'seasonings' => ['nullable', 'array'],
                        'ways' => ['nullable', 'array'],
                        'tags' => ['nullable', 'array'],
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
                        'except_package_id' => ['nullable', 'integer'],
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
            'except_package_id' => [
                'description' => '<span class="point">제외할 꾸러미 고유번호 (특정 꾸러미 제외하고 보고싶을 때)</span>',
            ],
            'is_bookmark' => [
                'description' => '<span class="point">북마크 여부 (1 - 내가 북마크한것만 보기)</span>',
            ],
            'title' => [
                'description' => '<span class="point">제목</span>',
            ],
            'description' => [
                'description' => '<span class="point">내용</span>',
            ],
            'materials' => [
                'description' => '<span class="point">필수재료 목록 [{title: "당근", count: "1개"}]</span>',
            ],
            'seasonings' => [
                'description' => '<span class="point">양념(간) 목록 [{title: "당근", count: "1개"}]</span>',
            ],
            'ways' => [
                'description' => '<span class="point">레시피 순서 목록 ["당근을 썰어요"]</span>',
            ],
            'tags' => [
                'description' => '<span class="point">태그 목록 [{id:"고유번호"}]</span>',
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
