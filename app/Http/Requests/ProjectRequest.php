<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
                        'product_id' => ['required', 'integer'],
                        'files' => ['required', 'array'],
                        'started_at' => ['required', 'date'],
                        'finished_at' => ['required', 'date'],
                        'count_goal' => ['required', 'integer'],
                        'count_participate' => ['required', 'integer'],
                        'tag_ids' => ['nullable', 'array'],
                    ];

                case 'update':
                    return [
                        'product_id' => ['required', 'integer'],
                        'files' => ['required', 'array'],
                        'started_at' => ['required', 'date'],
                        'finished_at' => ['required', 'date'],
                        'count_goal' => ['required', 'integer'],
                        'count_participate' => ['required', 'integer'],
                        'tag_ids' => ['nullable', 'array'],
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
            'started_at' => [
                'description' => '<span class="point">시작일자</span>',
            ],
            'finished_at' => [
                'description' => '<span class="point">종료일자</span>',
            ],
            'count_goal' => [
                'description' => '<span class="point">목표인원</span>',
            ],
            'count_participate' => [
                'description' => '<span class="point">참여인원</span>',
            ],
            'tag_ids' => [
                'description' => '<span class="point">태그고유번호목록</span>',
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
