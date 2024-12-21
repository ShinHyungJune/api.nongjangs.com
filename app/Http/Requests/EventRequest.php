<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
                        'coupon_group_id' => ['nullable', 'integer'],
                        'title' => ['required', 'string', 'max:500'],
                        'description' => ['required', 'string', 'max:50000'],
                        'started_at' => ['required', 'date'],
                        'finished_at' => ['required', 'date'],
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
            // 이 모델만 쓰이는 애들
            'coupon_group_id' => [
                'description' => '<span class="point">쿠폰그룹 고유번호 (쿠폰이벤트일때만 연결, select로 구성)</span>',
            ],
            'title' => [
                'description' => '<span class="point">제목</span>',
            ],
            'description' => [
                'description' => '<span class="point">내용</span>',
            ],
            'started_at' => [
                'description' => '<span class="point">시작일</span>',
            ],
            'finished_at' => [
                'description' => '<span class="point">종료일</span>',
            ],

            // 늘 쓰이는 애들
            'word' => [
                'description' => '<span class="point">검색어</span>',
            ],
            'ids' => [
                'description' => '<span class="point">선택한 대상들의 고유번호 목록</span>',
                // 'example' => '',
            ],
            'files' => [
                'description' => '<span class="point">이미지 파일 목록 input-images(multiple=false) 사용</span>',
                // 'example' => '',
            ],
            'files_remove_ids' => [
                'description' => '<span class="point">삭제할 미디어 파일 대상들의 고유번호 목록</span>',
                // 'example' => '',
            ],
        ];
    }
}
