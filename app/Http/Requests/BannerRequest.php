<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
                        'type' => ['nullable', 'integer'],
                        'word' => ['nullable', 'string', 'max:500']
                    ];

                case 'store':
                    return [
                        'files' => ['nullable', 'array'],
                        'color' => ['nullable', 'string', 'max:500'],
                        'type' => ['nullable', 'integer'],
                        'tag' => ['nullable', 'string', 'max:500'],
                        'title' => ['required', 'string', 'max:500'],
                        'description' => ['nullable', 'string', 'max:500'],
                        'url' => ['nullable', 'string', 'max:500'],
                    ];

                case 'update':
                    return [
                        'files' => ['nullable', 'array'],
                        'files_remove_ids' => ['nullable', 'array'],
                        'color' => ['nullable', 'string', 'max:500'],
                        'type' => ['nullable', 'integer'],
                        'tag' => ['nullable', 'string', 'max:500'],
                        'title' => ['required', 'string', 'max:500'],
                        'description' => ['nullable', 'string', 'max:500'],
                        'url' => ['nullable', 'string', 'max:500'],
                    ];

                case 'destroy':
                    return [
                        'ids' => ['required', 'array'],
                    ];

                default:
                    return [

                    ];
            }
        }else{
            switch ($method){
                case 'index':
                    return [
                        'type' => ['nullable', 'integer']
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
            'type' => [
                'description' => '<span class="point">유형 (1 - 메인배너 | 2 - 카테고리배너 | 3 - 중간배너 | 4 - 띠배너)</span>',
                // 'example' => '',
            ],
            'tag' => [
                'description' => '<span class="point">태그</span>',
                // 'example' => '',
            ],
            'title' => [
                'description' => '<span class="point">제목</span>',
                // 'example' => '',
            ],
            'description' => [
                'description' => '<span class="point">설명</span>',
                // 'example' => '',
            ],
            'url' => [
                'description' => '<span class="point">URL</span>',
                // 'example' => '',
            ],
            
            'ids' => [
                'description' => '<span class="point">선택한 대상들의 고유번호 목록</span>',
            ],
            'files' => [
                'description' => '<span class="point">이미지 input-images(multiple=false)</span>',
            ],
            'files_remove_ids' => [
                'word' => '<span class="point">삭제할 미디어 파일 대상들의 고유번호 목록</span>',
                // 'example' => '',
            ],
        ];
    }
}
