<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
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
                        'preset_product_id' => ['nullable', 'integer'],
                    ];

                case 'store':
                    return [
                        'description' => ['required', 'string', 'max:50000'],
                        'preset_product_id' => ['required', 'integer'],
                    ];

                case 'update':
                    return [
                        'description' => ['required', 'string', 'max:50000'],
                        'preset_product_id' => ['required', 'integer'],
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
                        'preset_product_uuid' => ['required', 'string', 'max:500'],
                    ];

                case 'store':
                    return [
                        'preset_product_uuid' => ['required', 'string', 'max:500'],
                        'description' => ['required','string','max:500']
                    ];

                default:
                    return [];
            }
        }

    }

    public function bodyParameters()
    {
        return [
            'preset_product_uuid' => [
                'description' => '<span class="point">연관상품 난수버전 고유번호</span>',
                // 'example' => '',
            ],
            'description' => [
                'description' => '<span class="point">내용</span>',
                // 'example' => '',
            ],
        ];
    }
}
