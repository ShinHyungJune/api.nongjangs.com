<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryRequest extends FormRequest
{
    public function rules(): array
    {
        $method = $this->route()->getActionMethod();
        $admin = strpos($this->route()->getPrefix(), 'admin') !== false;

        if ($admin) {
            switch ($method) {
                case 'index':
                    return [
                        'word' => ['nullable', 'string', 'max:500'],
                        'user_id' => ['nullable', 'integer'],
                    ];

                case 'store':
                    return [
                        'user_id' => ['required', 'exists:users'],
                        'main' => ['boolean'],
                        'title' => ['required'],
                        'name' => ['required'],
                        'contact' => ['required'],
                        'address' => ['required'],
                        'address_detail' => ['required'],
                        'address_zipcode' => ['required'],
                    ];

                case 'update':
                    return [
                        'user_id' => ['required', 'exists:users'],
                        'main' => ['boolean'],
                        'title' => ['required'],
                        'name' => ['required'],
                        'contact' => ['required'],
                        'address' => ['required'],
                        'address_detail' => ['required'],
                        'address_zipcode' => ['required'],//
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
                        'main' => ['required', 'boolean'],
                        'title' => ['required', 'string', 'max:500'],
                        'name' => ['required', 'string', 'max:500'],
                        'contact' => ['required', 'string', 'max:500'],
                        'address' => ['required', 'string', 'max:500'],
                        'address_detail' => ['required', 'string', 'max:500'],
                        'address_zipcode' => ['required', 'string', 'max:500'],
                    ];

                case 'update':
                    return [
                        'main' => ['required', 'boolean'],
                        'title' => ['required', 'string', 'max:500'],
                        'name' => ['required', 'string', 'max:500'],
                        'contact' => ['required', 'string', 'max:500'],
                        'address' => ['required', 'string', 'max:500'],
                        'address_detail' => ['required', 'string', 'max:500'],
                        'address_zipcode' => ['required', 'string', 'max:500'],
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
