<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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

        switch ($method){
            case 'index':
                return [
                    'page' => ["nullable", "integer"],
                    'take' => ["nullable", "integer"],
                    'order_by' => ["nullable", "string", "max:500"],
                    'align' => ['nullable', 'string', 'max:500'],
                    'category_id' => ['nullable', 'integer'],
                    'random' => ['nullable', 'boolean'],
                    'recommend' => ['nullable', 'boolean'],
                    'price_min' => ['nullable', 'integer'],
                    'price_max' => ['nullable', 'integer'],
                    'word' => ['nullable', 'string', 'max:500'],
                ];

            case 'store':
                return [
                    'category_ids' => ['required', 'array'],
                    'imgs' => ['required', 'array'],
                    'imgs_prototype' => ['nullable', 'array'],
                    'imgs_real' => ['nullable', 'array'],
                    'imgs_circle' => ['nullable', 'array'],

                    'count_order' => ['required', 'integer'],
                    'open' => ['required', 'boolean'],
                    'custom' => ['required', 'boolean'],
                    'title' => ['required', 'string', 'max:500'],
                    'description' => ['required', 'string', 'max:50000'],
                    'summary' => ['required', 'string', 'max:50000'],
                    'price_discount' => ['required', 'integer'],
                    'price_origin' => ['required', 'integer'],
                    'pop' => ['required', 'boolean'],
                    'special' => ['required', 'boolean'],
                    'recommend' => ['required', 'boolean'],
                    'empty' => ['required', 'boolean'],
                    'duration' => ['nullable', 'string', 'max:500'],
                    'texture' => ['nullable', 'string', 'max:500'],
                    // 'type_delivery' => ['nullable', 'text', 'max:500'],
                    'creator' => ['nullable', 'string', 'max:500'],
                    'case' => ['nullable', 'string', 'max:500'],
                    'way_to_create' => ['nullable', 'string', 'max:500'],
                    'way_to_delivery' => ['nullable', 'string', 'max:500'],
                ];

            case 'update':
                return [
                    'category_ids' => ['required', 'array'],
                    'imgs' => ['nullable', 'array'],
                    'imgs_prototype' => ['nullable', 'array'],
                    'imgs_real' => ['nullable', 'array'],
                    'imgs_circle' => ['nullable', 'array'],

                    'open' => ['required', 'boolean'],
                    'custom' => ['required', 'boolean'],
                    'title' => ['required', 'string', 'max:500'],
                    'description' => ['required', 'string', 'max:50000'],
                    'summary' => ['required', 'string', 'max:50000'],
                    'price_discount' => ['required', 'integer'],
                    'price_origin' => ['required', 'integer'],
                    'pop' => ['required', 'boolean'],
                    'special' => ['required', 'boolean'],
                    'recommend' => ['required', 'boolean'],
                    'empty' => ['required', 'boolean'],
                    'duration' => ['nullable', 'string', 'max:500'],
                    'texture' => ['nullable', 'string', 'max:500'],
                    'type_delivery' => ['nullable', 'string', 'max:500'],
                    'creator' => ['nullable', 'string', 'max:500'],
                    'case' => ['nullable', 'string', 'max:500'],
                    'way_to_create' => ['nullable', 'string', 'max:500'],
                    'way_to_delivery' => ['nullable', 'string', 'max:500'],
                ];

            case 'updateActive':
                return [
                    'ids' => ['required', 'array'],
                    'active' => ['required', 'boolean'],
                ];

            default:
                return [];
        }
    }

    public function bodyParameters()
    {
        return [
            'page' => [
                'description' => '<span class="point">(선택) 조회할 페이지</span>',
            ],
            'take' => [
                'description' => '<span class="point">(선택) 가져올 개수</span>',
            ],
            'order_by' => [
                'description' => '<span class="point">(선택) 정렬기준 (count_order - 주문수 | created_at - 생성일자 | price - 가격 | recommend - 추천)</span>'
            ],
            'align' => [
                'description' => '<span class="point">(선택) 정렬순서 (desc - 내림차순 | asc - 오름차순)</span>',
            ],
            'category_id' => [
                'description' => '<span class="point">(선택) 카테고리 고유번호 (특정 카테고리것만 보고 싶을 때)</span>',
            ],
            'random' => [
                'description' => '<span class="point">(선택) 랜덤순으로 볼지 여부 (랜덤순으로 보고싶다면 1 넘기기)</span>',
            ],
            'recommend' => [
                'description' => '<span class="point">(선택) 추천여부 (추천상품만 보고싶다면 1 넘기기)</span>'
            ],
            'price_min' => [
                'description' => '<span class="point">(선택) 최대금액</span>'
            ],
            'price_max' => [
                'description' => '<span class="point">(선택) 최소금액</span>'
            ],
            'word' => [
                'description' => '<span class="point">(선택) 검색어</span>'
            ],
        ];
    }
}
