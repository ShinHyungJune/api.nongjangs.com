<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
            switch ($method){
                case 'index':
                    return [
                        'product_id' => 'nullable|integer',
                        'order_by' => 'nullable|string|max:500',
                    ];

                case 'store':
                    return [
                        'coupon_group_ids' => ['required', 'array']
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
                'description' => '<span class="point">상품 고유번호 (특정 상품에 사용 가능한 쿠폰목록 보고싶을 때)</span>',
            ],
            'coupon_group_ids' => [
                'description' => '<span class="point">쿠폰그룹 고유번호 목록</span>',
            ],
            'order_by' => [
                'description' => '<span class="point">value - 할인값 | created_at - 등록순</span>',
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
