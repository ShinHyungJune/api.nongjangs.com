<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FarmStoryRequest extends FormRequest
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
                        'farm_id' => ['required', 'exists:factories'],
                        'title' => ['required'],
                        'description' => ['required'],
                        'internal' => ['required', 'boolean'],
                    ];

                case 'update':
                    return [
                        'farm_id' => ['required', 'exists:factories'],
                        'title' => ['required'],
                        'description' => ['required'],
                        'internal' => ['required', 'boolean'],
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
                        'word' => ['nullable', 'string', 'max:500'],
                        'tag_ids' => ['nullable', 'array'],
                        'farm_id' => ['nullable', 'integer'],
                        'exclude_farm_id' => ['nullable', 'integer'],
                        'internal' => ['nullable', 'boolean'],
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
            'order_by' => [
                'description' => '<span class="point">정렬기준 (count_like - 추천수 | created_at - 등록일자)</span>',
            ],
            'tag_ids' => [
                'description' => '<span class="point">관련태그 고유번호 목록</span>',
            ],
            'farm_id' => [
                'description' => '<span class="point">농가 고유번호 (특정 농가의 글만 보고싶을 때)</span>',
            ],
            'exclude_farm_id' => [
                'description' => '<span class="point">제외할 농가 고유번호 (특정 농가것만 빼고 보고싶을 때)</span>',
            ],
            'title' => [
                'description' => '<span class="point">제목</span>',
            ],
            'description' => [
                'description' => '<span class="point">내용</span>',
            ],
            'internal' => [
                'description' => '<span class="point">내부(농장스) 글 여부</span>',
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
