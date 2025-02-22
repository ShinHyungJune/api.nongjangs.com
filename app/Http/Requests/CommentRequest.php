<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
                        'word' => ['nullable', 'string', 'max:500']
                    ];

                case 'store':
                    return [
                        'user_id' => ['required', 'exists:users'],
                        'commentable_id' => ['required'],
                        'commentable_type' => ['required'],
                        'description' => ['required'],
                    ];

                case 'update':
                    return [
                        'user_id' => ['required', 'exists:users'],
                        'commentable_id' => ['required'],
                        'commentable_type' => ['required'],
                        'description' => ['required'],//
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
                        'user_id' => ['nullable', 'integer']
                    ];

                case 'store':
                    return [
                        'commentable_id' => ['required', 'integer'],
                        'commentable_type' => ['required', 'string', 'max:500'],
                        'description' => ['required', 'string', 'max:5000'],
                    ];

                case 'update':
                    return [
                        'description' => ['required', 'string', 'max:5000'],
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
            'user_id' => [
                'description' => '<span class="point">사용자 고유번호</span>',
            ],
            'commentable_id' => [
                'description' => '<span class="point">대상 고유번호</span>',
            ],
            'commentable_type' => [
                'description' => '<span class="point">대상 모델명 (App\Models\VegetableStory - 채소이야기)</span>',
            ],
            'description' => [
                'description' => '<span class="point">내용</span>',
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
