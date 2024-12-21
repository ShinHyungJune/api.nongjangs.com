<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QnaRequest extends FormRequest
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
                        'answer' => ['required', 'string', 'max:50000'],
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
                        'qna_category_id' => ['required', 'integer'],
                        'title' => ['required', 'string', 'max:500'],
                        'description' => ['required', 'string', 'max:10000'],
                        'imgs' => ['nullable', 'array'],
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
            'email' => [
                'qna_category_id' => '<span class="point">문의카테고리 고유번호</span>',
                // 'example' => '',
            ],
            'title' => [
                'description' => '<span class="point">제목</span>',
                // 'example' => '',
            ],
            'description' => [
                'description' => '<span class="point">내용</span>',
                // 'example' => '',
            ],
            'imgs' => [
                'description' => '<span class="point">이미지 목록 input-images 사용</span>',
                // 'example' => '',
            ],
        ];
    }
}
