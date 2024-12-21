<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponHistoryRequest extends FormRequest
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
                    'increase' => ['nullable', 'boolean']
                ];

            default:
                return [];
        }
    }

    public function bodyParameters()
    {
        return [
            'increase' => [
                'description' => '<span class="point">증감여부 (1 - 증가 | 0 - 감소)</span>',
            ],
        ];
    }
}
