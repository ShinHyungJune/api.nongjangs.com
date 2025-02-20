<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PointHistoryRequest extends FormRequest
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
                        'start_expired_at' => ['nullable', 'date'],
                        'finish_expired_at' => ['nullable', 'date'],
                        'user_id' => ['nullable', 'integer'],
                        'word' => ['nullable', 'string', 'max:500'],
                    ];

                case 'store':
                    return [
                        'user_id' => ['required', 'integer'],
                        'increase' => ['required', 'boolean'],
                        'point' => ['required', 'integer', 'min:1'],
                        'memo' => ['required', 'string', 'max:500'],
                    ];

                case 'update':
                    return [
                        '' => []
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
                        'increase' => ['nullable', 'boolean']
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
            'user_id' => [
                'description' => '<span class="point">사용자 고유번호</span>',
            ],
            'point' => [
                'description' => '<span class="point">포인트</span>',
            ],
            'memo' => [
                'description' => '<span class="point">메모</span>',
            ],
            'start_expired_at' => [
                'description' => '<span class="point">유효기간 시작일자</span>',
            ],
            'finish_expired_at' => [
                'description' => '<span class="point">유효기간 종료일자</span>',
            ],
            'increase' => [
                'description' => '<span class="point">증감여부 (1 - 증가 | 0 - 감소)</span>',
            ],
        ];
    }
}
