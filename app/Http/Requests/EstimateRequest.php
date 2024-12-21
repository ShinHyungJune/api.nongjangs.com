<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstimateRequest extends FormRequest
{
    public function rules(): array
    {
        $method = $this->route()->getActionMethod();
        $admin = strpos($this->route()->getPrefix(), 'admin') !== false;

        if($admin){
            switch ($method){
                case 'index':
                    return [
                        'word' => ['nullable', 'string', 'max:500']
                    ];

                case 'store':
                    return [
                        '' => []
                    ];

                case 'update':
                    return [
                        '' => []
                    ];

                case 'destroy':
                    return [
                        'ids' => ['required', 'array'],
                    ];

                default:
                    return [];
            }
        }else{
            switch ($method) {
                case 'store': // 생성
                    return [
                        'email' => ['required', 'email', 'max:254'],
                        'company_name' => ['required', 'string', 'max:500'],
                        'name' => ['required', 'string', 'max:500'],
                        'contact' => ['required', 'string', 'max:500'],
                        'title' => ['nullable', 'string', 'max:500'],
                        'description' => ['required', 'string', 'max:500'],
                        'budget' => ['required', 'string', 'max:500'],
                        'count' => ['nullable', 'string', 'max:500'],
                        'files' => ['nullable'],
                        'need_finished_at' => ['required', 'string', 'max:500'],
                    ];

                case 'update': // 수정
                    return [
                        'email' => ['required', 'email', 'max:254'],
                        'company_name' => ['required', 'string', 'max:500'],
                        'name' => ['required', 'string', 'max:500'],
                        'contact' => ['required', 'string', 'max:500'],
                        'title' => ['nullable', 'string', 'max:500'],
                        'description' => ['required', 'string', 'max:500'],
                        'budget' => ['required', 'string', 'max:500'],
                        'count' => ['nullable', 'string', 'max:500'],
                        'files' => ['nullable'],
                        'need_finished_at' => ['required', 'string', 'max:500'],
                    ];

                default:
                    return [];
            }
        }
    }

    public function authorize(): bool
    {
        return true;
    }


    public function bodyParameters()
    {
        return [
            'email' => [
                'description' => '<span class="point">이메일</span>',
                // 'example' => '',
            ],
            'name' => [
                'description' => '<span class="point">이름</span>',
                // 'example' => '',
            ],
            'contact' => [
                'description' => '<span class="point">연락처</span>',
                // 'example' => '',
            ],
            'title' => [
                'description' => '<span class="point">제목</span>',
                // 'example' => '',
            ],
            'description' => [
                'description' => '<span class="point">내용</span>',
                // 'example' => '',
            ],
            'budget' => [
                'description' => '<span class="point">예산</span>',
                // 'example' => '',
            ],
            'count' => [
                'description' => '<span class="point">(선택) 필요상품개수</span>',
                // 'example' => '',
            ],
            'files' => [
                'description' => '<span class="point">(선택) 파일 객체 배열</span>'
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
}
