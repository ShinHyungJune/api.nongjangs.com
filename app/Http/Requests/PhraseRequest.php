<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhraseRequest extends FormRequest
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
                        'phrase_product_category_id' => ['required', 'integer'],
                        'phrase_receiver_category_id' => ['required', 'integer'],
                        'description' => ['required', 'string', 'max:500'],
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
                        'take' => ['nullable', 'integer'],
                        'phrase_product_category_id' => ['nullable', 'integer'],
                        'phrase_receiver_category_id' => ['nullable', 'integer'],
                        'word' => ['nullable', 'string', 'max:500'],
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
            'take' => [
                'description' => '<span class="point">(선택) 가져올 개수</span>'
            ],
            'phrase_product_category_id' => [
                'description' => '<span class="point">(선택) 문구상품 카테고리 고유번호</span>'
            ],
            'phrase_receiver_category_id' => [
                'description' => '<span class="point">(선택) 문구수신자 카테고리 고유번호</span>'
            ],
            'word' => [
                'description' => '<span class="point">(선택) 검색어</span>'
            ]
        ];
    }


}
