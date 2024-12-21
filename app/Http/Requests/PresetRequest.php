<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresetRequest extends FormRequest
{
    public function rules()
    {
        $method = $this->route()->getActionMethod();

        switch ($method) {
            case 'store':
                return [
                    'products' => ['required', 'array', 'min:1'],
                    'additionalProducts' => ['nullable', 'array'],
                ];



            default:
                return [

                ];
        }
    }

    public function bodyParameters()
    {
        return [
            'can_review' => [
                'description' => '<span class="point">(선택) 리뷰가능여부 - 리뷰가능한 목록만 보고싶을 때 1로 넘기기</span>'
            ],
            'count' => [
                'description' => '<span class="point">개수</span>'
            ],

            'products' => [
                'description' => '<span class="point">상품목록 <br/>[{id: 고유번호, count: 개수, size_id: 선택한 사이즈 고유번호, color_id: 선택한 컬러 고유번호}]</span>'
            ],
            'additionalProducts' => [
                'description' => '<span class="point">추가상품목록 <br/>[{id: 고유번호, count: 개수}]</span>'
            ],
            'title' => [
                'description' => '<span class="point">(선택) 제목</span>'
            ],
            'receiver' => [
                'description' => '<span class="point">(선택) 받는사람</span>'
            ],
            'description' => [
                'description' => '<span class="point">(선택) 본문</span>'
            ],
            'date' => [
                'description' => '<span class="point">(선택) 제품 삽입 날짜</span>'
            ],
            'sender' => [
                'description' => '<span class="point">(선택) 주는사람/단체명</span>'
            ],
            'logo_url' => [
                'description' => '<span class="point">(선택) 로고 URL</span>'
            ],
            'type_stamp' => [
                'description' => '<span class="point">(선택) 직인 사용여부</span>'
            ],
            'requirement' => [
                'description' => '<span class="point">(선택) 요청사항</span>'
            ],

            'sheet' => [
                'description' => '<span class="point">(선택) 주문서 파일 목록 (여러파일 첨부항 수 있는 input-files 컴포넌트 사용필요)</span>'
            ],
            'logo' => [
                'description' => '<span class="point">(선택) 로고 파일 목록 (여러파일 첨부항 수 있는 input-files 컴포넌트 사용필요)</span>'
            ],
            'stamp' => [
                'description' => '<span class="point">(선택) 직인 파일 목록 (여러파일 첨부항 수 있는 input-files 컴포넌트 사용필요)</span>'
            ],
        ];
    }
}
