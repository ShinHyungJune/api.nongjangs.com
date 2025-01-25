<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
                        'report_category_id' => ['required', 'exists:report_categories'],
                        'description' => ['nullable'],
                    ];

                case 'update':
                    return [
                        'report_category_id' => ['required', 'exists:report_categories'],
                        'description' => ['nullable'],//
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
                        'report_category_id' => ['required', 'integer'],
                        'reportable_id' => ['required', 'integer'],
                        'reportable_type' => ['required', 'string', 'max:500'],
                        'description' => ['required', 'string', 'max:50000'],
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
            'report_category_id' => [
                'description' => '<span class="point">신고유형 고유번호</span>',
            ],
            'reportable_id' => [
                'description' => '<span class="point">신고대상 고유번호</span>',
            ],
            'reportable_type' => [
                'description' => '<span class="point">신고대상 모델명 (App\Models\Product - 상품 | App\Models\Review - 리뷰 | App\Models\Comment - 댓글 | App\Models\VegetableStory - 채소이야기 | App\Models\Recipe 레시피)</span>',
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
