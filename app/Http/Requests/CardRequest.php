<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
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
                        'name' => ['required'],
                        'number' => ['required'],
                        'expiry_year' => ['required'],
                        'expiry_month' => ['required'],
                        'birth_or_business_number' => ['required'],
                        'password' => ['required'],
                    ];

                case 'update':
                    return [
                        'name' => ['required'],
                        'number' => ['required'],
                        'expiry_year' => ['required'],
                        'expiry_month' => ['required'],
                        'birth_or_business_number' => ['required'],
                        'password' => ['required'],
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
                        'customer_uid' => ['required','string','max:500'],
                        'name' => ['required','string','max:500'],
                        /*
                        'name' => ['required'],
                        'number' => ['required'],
                        'expiry_year' => ['required'],
                        'expiry_month' => ['required'],
                        'birth_or_business_number' => ['required'],
                        'password' => ['required'],
                        */
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
            'number' => [
                'description' => '<span class="point">카드번호</span>',
            ],
            'expiry_month' => [
                'description' => '<span class="point">만료월</span>',
            ],
            'expiry_year' => [
                'description' => '<span class="point">만료년도</span>',
            ],
            'birth_or_business_number' => [
                'description' => '<span class="point">생년월일 / 사업자등록번호</span>',
            ],
            'password' => [
                'description' => '<span class="point">비밀번호</span>',
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
