<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
                        'count' => ['required', 'integer'],
                        'will_deliveried_at' => ['nullable', 'date'],
                        'tax' => ['boolean'],
                        'start_pack_wait_at' => ['nullable', 'date'],
                        'finish_pack_wait_at' => ['nullable', 'date'],
                        'start_pack_at' => ['nullable', 'date'],
                        'finish_pack_at' => ['nullable', 'date'],
                        'start_delivery_ready_at' => ['nullable', 'date'],
                        'finish_delivery_ready_at' => ['nullable', 'date'],
                        'start_will_out_at' => ['nullable', 'date'],
                        'finish_will_out_at' => ['nullable', 'date'],
                    ];

                case 'update':
                    return [
                        'count' => ['required', 'integer'],
                        'will_deliveried_at' => ['nullable', 'date'],
                        'tax' => ['boolean'],
                        'start_pack_wait_at' => ['nullable', 'date'],
                        'finish_pack_wait_at' => ['nullable', 'date'],
                        'start_pack_at' => ['nullable', 'date'],
                        'finish_pack_at' => ['nullable', 'date'],
                        'start_delivery_ready_at' => ['nullable', 'date'],
                        'finish_delivery_ready_at' => ['nullable', 'date'],
                        'start_will_out_at' => ['nullable', 'date'],
                        'finish_will_out_at' => ['nullable', 'date'],//
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
            'example' => [
                'description' => '<span class="point"></span>',
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