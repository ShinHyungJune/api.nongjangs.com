<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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

                    ];

                case 'update':
                    return [
                        'product_id' => ['required', 'integer'],
                        'best' => ['required', 'boolean'],
                        'score' => ['required', 'integer', 'min:1', 'max:5'],
                        'description' => ['required', 'string', 'max:50000'],
                        'hide' => ['required', 'boolean'],
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
                        'take' => 'nullable|integer|max:100',
                        'order_by' => 'nullable|string|max:500',
                        'align' => 'nullable|string|max:500',

                        'has_column' => 'nullable|string|max:500',
                        'product_id' => 'nullable|integer',
                        'package_id' => 'nullable|integer',
                        'best' => 'nullable|boolean',
                        'type' => 'nullable|integer',
                        'user_id' => 'nullable|integer',
                        'photo' => 'nullable|boolean',
                    ];

                case 'store':
                    return [
                        'preset_product_id' => ['required', 'integer'],
                        'description' => ['required', 'string', 'max:10000'],
                        'score' => ['required', 'integer', 'min:1', 'max:5'],
                        'imgs' => ['nullable', 'array']
                    ];

                case 'update':
                    return [
                        'description' => ['required', 'string', 'max:10000'],
                        'score' => ['required', 'integer', 'min:1', 'max:5'],
                        'imgs' => ['nullable', 'array'],
                        'imgs_remove_ids' => ['nullable', 'array']
                    ];

                default:
                    return [];
            }
        }
    }

    public function bodyParameters()
    {
        return [
            'hide' => [
                'description' => '<span class="point">가림여부</span>',
            ],
            'take' => [
                'description' => '<span class="point">가져올 개수</span>',
            ],
            'order_by' => [ // best - 베스트여부 | created_at 생성
                'description' => '<span class="point">정렬기준 (best - 베스트여부 | created_at 생성일자 | count_like 좋아요순)</span>',
            ],
            'align' => [
                'description' => '<span class="point">정렬순서 default desc (desc - 내림차순 | asc - 오름차순)</span>',
            ],
            'product_id' => [
                'description' => '<span class="point">상품 고유번호</span>',
            ],
            'user_id' => [
                'description' => '<span class="point">사용자 고유번호</span>',
            ],
            'photo' => [
                'description' => '<span class="point">포토리뷰여부 (포토리뷰만 보고싶으면 1)</span>',
            ],
            'preset_product_id' => [
                'description' => '<span class="point">연관상품 고유번호</span>',
            ],
            'description' => [
                'description' => '<span class="point">내용</span>',
            ],
            'score' => [
                'description' => '<span class="point">점수</span>',
            ],
            'imgs' => [
                'description' => '<span class="point">이미지 목록 input-images 사용</span>',
            ],
            'imgs_remove_ids' => [
                'description' => '<span class="point">삭제할 이미지 고유번호 목록</span>',
            ],
        ];
    }
}
