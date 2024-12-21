<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
            case 'store':
                return [
                    'preset_id' => ['required', 'integer'],
                ];

            case 'update':
                return [
                    'preset_id' => ['required', 'integer'],
                    'count' => ['required', 'integer', 'min:1']
                ];

            case 'destroy':
                return [
                    'preset_ids' => ['required', 'array'],
                ];

            default:
                return [

                ];
        }
    }

    public function bodyParameters()
    {
        return [
            'preset_id' => [
                'description' => '<span class="point">상품조합 고유번호</span>'
            ],
            'preset_ids' => [
                'description' => '<span class="point">상품조합 고유번호 목록</span>'
            ],
            'count' => [
                'description' => '<span class="point">개수</span>'
            ],
        ];
    }
}
