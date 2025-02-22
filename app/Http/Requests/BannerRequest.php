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
                        'type' => ['required', 'integer'],
                        'pc' => ['nullable', 'array'],
                        'mobile' => ['nullable', 'array'],
                        'title' => ['required', 'string', 'max:500'],
                        'subtitle' => ['nullable', 'string', 'max:500'],
                        'url' => ['nullable', 'string', 'max:500'],
                        'button' => ['required', 'string', 'max:500'],
                        'color_text' => ['required', 'string', 'max:500'],
                        'color_button' => ['required', 'string', 'max:500'],
                        'started_at' => ['required', 'date'],
                        'finished_at' => ['required', 'date'],
                    ];

                case 'update':
                    return [
                        'type' => ['required', 'integer'],
                        'pc' => ['nullable', 'array'],
                        'mobile' => ['nullable', 'array'],
                        'title' => ['required', 'string', 'max:500'],
                        'subtitle' => ['nullable', 'string', 'max:500'],
                        'url' => ['nullable', 'string', 'max:500'],
                        'button' => ['required', 'string', 'max:500'],
                        'color_text' => ['required', 'string', 'max:500'],
                        'color_button' => ['required', 'string', 'max:500'],
                        'started_at' => ['required', 'date'],
                        'finished_at' => ['required', 'date'],
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
                'description' => '<span class="point">유형 (1 - 메인배너 | 2 - 농가이야기 | 3 - 정기구독 | 4 - 직거래장터)</span>',
                // 'example' => '',
            ],
            'title' => [
                'description' => '<span class="point">제목</span>',
                // 'example' => '',
            ],
            'subtitle' => [
                'description' => '<span class="point">부제목</span>',
                // 'example' => '',
            ],
            'url' => [
                'description' => '<span class="point">이동 URL</span>',
                // 'example' => '',
            ],
            'button' => [
                'description' => '<span class="point">버튼명</span>',
                // 'example' => '',
            ],
            'color_text' => [
                'description' => '<span class="point">글씨 색상</span>',
                // 'example' => '',
            ],
            'color_button' => [
                'description' => '<span class="point">버튼 색상</span>',
                // 'example' => '',
            ],
            'started_at' => [
                'description' => '<span class="point">노출시작일</span>',
                // 'example' => '',
            ],
            'finished_at' => [
                'description' => '<span class="point">노출종료일</span>',
                // 'example' => '',
            ],
            
            'ids' => [
                'description' => '<span class="point">선택한 대상들의 고유번호 목록</span>',
            ],
            'pc' => [
                'description' => '<span class="point">PC 이미지 input-images(multiple=false)</span>',
            ],
            'mobile' => [
                'description' => '<span class="point">모바일 이미지 input-images(multiple=false)</span>',
            ],
            'pc_remove_ids' => [
                'word' => '<span class="point">삭제할 미디어 파일 대상들의 고유번호 목록</span>',
                // 'example' => '',
            ],
            'mobile_remove_ids' => [
                'word' => '<span class="point">삭제할 미디어 파일 대상들의 고유번호 목록</span>',
                // 'example' => '',
            ],
        ];
    }
}
