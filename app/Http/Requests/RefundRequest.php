<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
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
                    'preset_product_id' => 'nullable|integer'
                ];

            case 'store':
                return [
                    'preset_product_id' => ['required', 'integer'],
                    'category' => ['required', 'string', 'max:3000'],
                    'title' => ['required', 'string', 'max:3000'],
                    'description' => ['required', 'string', 'max:3000'],
                ];

            default:
                return [];
        }
    }

    public function bodyParameters()
    {
        return [
            'preset_product_id' => [
                'description' => '<span class="point">연관상품 고유번호</span>',
                // 'example' => '',
            ],
            'category' => [
                'description' => '<span class="point">카테고리</span>',
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

        ];
    }
}
