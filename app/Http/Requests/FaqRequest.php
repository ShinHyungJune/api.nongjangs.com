<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
                        'faq_category_id' => ['required', 'integer'],
                        'title' => ['required', 'string', 'max:500'],
                        'description' => ['required', 'string', 'max:50000'],
                    ];

                case 'update':
                    return [
                        'faq_category_id' => ['required', 'integer'],
                        'title' => ['required', 'string', 'max:500'],
                        'description' => ['required', 'string', 'max:50000'],
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
                        'faq_category_id' => ["nullable", "integer"],
                        'word' => ['nullable', 'string', 'max:500']
                    ];

                default:
                    return [];
            }
        }

    }

    public function bodyParameters()
    {
        return [
            'faq_category_id' => [
                'description' => '<span class="point">카테고리 고유번호</span>',
            ],
            'title' => [
                'description' => '<span class="point">제목</span>',
            ],
            'description' => [
                'description' => '<span class="point">내용</span>',
            ],
            'word' => [
                'description' => '<span class="point">검색어</span>'
            ]
        ];
    }
}
