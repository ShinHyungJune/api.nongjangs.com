<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
                        'word' => ['nullable', 'string', 'max:500'],
                        'type' => ['nullable', 'integer'],
                        'category_id' => ['nullable', 'integer'],
                    ];

                case 'store':
                    return [
                        'type' => ['required', 'integer'],
                        'category_id' => ['nullable', 'integer'],
                        'title' => ['required', 'string', 'max:500'],
                    ];

                case 'update':
                    return [
                        'type' => ['required', 'integer'],
                        'category_id' => ['nullable', 'integer'],
                        'title' => ['required', 'string', 'max:500'],
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

                    ];

                case 'store':
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
            'word' => [
                'description' => '<span class="point">검색어</span>',
                // 'example' => '',
            ],
            'type' => [
                'description' => '<span class="point">유형 (1 - PRODUCT 직거래상품 | 2 - PACKAGE - 꾸러미)</span>',
                // 'example' => '',
            ],
            'category_id' => [
                'description' => '<span class="point">대분류 카테고리 고유번호 (소분류 카테고리 생성할 때 사용)</span>',
                // 'example' => '',
            ],


            'title' => [
                'description' => '<span class="point">제목</span>',
                // 'example' => '',
            ],

            'files' => [
                'description' => '<span class="point">시안 예시 이미지 input-images(multiple=false) 사용</span>',
                // 'example' => '',
            ],

            'files_remove_ids' => [
                'description' => '<span class="point">삭제할 미디어 파일 대상들의 고유번호 목록</span>',
                // 'example' => '',
            ],

            'ids' => [
                'description' => '<span class="point">검색어</span>',
                // 'example' => '',
            ],

        ];
    }
}
