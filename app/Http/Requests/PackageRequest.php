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
                        'tax' => ['boolean'],
                        'recipes' => ['nullable', 'array'],
                        'packageMaterials' => ['required', 'array', 'max:500'],
                    ];

                case 'update':
                    return [
                        'tax' => ['boolean'],
                        'recipes' => ['nullable', 'array'],
                        'packageMaterials' => ['required', 'array', 'max:500'],
                    ];

                case 'updateSchedule':
                    return [
                        'will_delivery_at' => ['required','date'],
                        'start_pack_wait_at' => ['required','date'],
                        'finish_pack_wait_at' => ['required','date'],
                        'start_pack_at' => ['required','date'],
                        'finish_pack_at' => ['required','date'],
                        'start_delivery_ready_at' => ['required','date'],
                        'finish_delivery_ready_at' => ['required','date'],
                        'start_will_out_at' => ['required','date'],
                        'finish_will_out_at' => ['required','date'],
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
            'will_delivery_at' => [
                'description' => '<span class="point">도착예정일</span>',
            ],
            'start_pack_wait_at' => [
                'description' => '<span class="point">구성대기 시작일</span>',
            ],
            'finish_pack_wait_at' => [
                'description' => '<span class="point">구성대기 종료일</span>',
            ],
            'start_pack_at' => [
                'description' => '<span class="point">품목구성 시작일</span>',
            ],
            'finish_pack_at' => [
                'description' => '<span class="point">품목구성 종료일</span>',
            ],
            'start_delivery_ready_at' => [
                'description' => '<span class="point">배송준비 시작일</span>',
            ],
            'finish_delivery_ready_at' => [
                'description' => '<span class="point">배송준비 종료일</span>',
            ],
            'start_will_out_at' => [
                'description' => '<span class="point">출고예정 시작일</span>',
            ],
            'finish_will_out_at' => [
                'description' => '<span class="point">출고예정 종료일</span>',
            ],

            'recipes' => [
                'description' => '<span class="point">레시피 목록 [{id:고유번호}]</span>',
            ],
            'tax' => [
                'description' => '<span class="point">과세여부 (1 - 과세 | 0 - 면세)</span>',
            ],
            'materials' => [
                'description' => '<span class="point">연결품목 목록 [{id:고유번호(수정일때만 넘기기), material_id: 품목 고유번호, type: 1 - 싱글 | 2 - 벙글 | 3 - 선택가능, count: 재고수, value: 값(50g -> 50), unit: 단위(50g -> g), price: 판매가, price_origin: 정가, tag_ids: 태그 고유번호목록}]</span>',
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
