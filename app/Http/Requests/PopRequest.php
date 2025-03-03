<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PopRequest extends FormRequest
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
                        'url' => ['nullable'],
                        'open' => ['boolean'],
                        'started_at' => ['required', 'date'],
                        'finished_at' => ['required', 'date'],
                        'files' => ['required', 'array'],
                        // 'order' => ['required', 'integer'],
                    ];

                case 'update':
                    return [
                        'title' => ['required'],
                        'url' => ['nullable'],
                        'open' => ['boolean'],
                        'started_at' => ['required', 'date'],
                        'finished_at' => ['required', 'date'],
                        'files' => ['nullable', 'array'],
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
            'title' => [
                'description' => '<span class="point">제목</span>',
            ],
            'url' => [
                'description' => '<span class="point">이동할 링크</span>',
            ],
            'open' => [
                'description' => '<span class="point">공개여부</span>',
            ],
            'started_at' => [
                'description' => '<span class="point">노출시작일</span>',
            ],
            'finished_at' => [
                'description' => '<span class="point">노출종료일</span>',
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
