<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
                        '' => []
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
                        'started_at' => 'nullable|string|max:500',
                        'finished_at' => 'nullable|string|max:500',
                        'word' => 'nullable|string|max:500',
                        'has_column' => 'nullable|string|max:500',
                        'states' => 'nullable|array',
                    ];

                case 'store':
                    return [
                        'presets' => 'required|array|max:100',
                    ];

                case 'update':
                    $data = [
                        'buyer_contact' => ['required','string','max:500'],
                        'buyer_email' => ['required','string','max:500'],
                        'buyer_name' => ['required','string','max:500'],

                        'delivery_name' => ['required','string','max:500'],
                        'delivery_contact' => ['required','string','max:500'],
                        'delivery_address' => ['required','string','max:500'],
                        'delivery_address_detail' => ['required','string','max:500'],
                        'delivery_address_zipcode' => ['required','string','max:500'],
                        'delivery_requirement' => ['nullable','string','max:500'],

                        'point_use' => ['required','integer'],

                        'pay_method_id' => ['required', 'integer'],
                    ];

                    return $data;

                case 'complete':
                    return [
                        "imp_uid" => "required|string|max:50000",
                        "merchant_uid" => "required|string|max:50000",
                    ];

                default:
                    return [];
            }
        }
    }

    public function bodyParameters()
    {
        return [
            'has_column' => ['description' => '<span class="point">특정데이터 보유여부 (package_id - 꾸러미 | product_id - 직거래 장터)</span>'],
            'started_at' => ['description' => '<span class="point">시작일자</span>'],
            'finished_at' => ['description' => '<span class="point">종료일자</span>'],
            'word' => ['description' => '<span class="point">검색어</span>'],
            'states' => ['description' => '<span class="point">상태목록 (PresetProduct의 상태 목록)</span>'],
            'presets' => ['description' => '<span class="point">상품조합목록</span>'],
            'buyer_name' => ['description' => '<span class="point">주문자 이름</span>'],
            'buyer_email' => ['description' => '<span class="point">주문자 이메일</span>'],
            'buyer_contact' => ['description' => '<span class="point">주문자 연락처</span>'],

            'delivery_name' => ['description' => '<span class="point">수취인 이름</span>'],
            'delivery_contact' => ['description' => '<span class="point">수취인 연락처</span>'],
            'delivery_address' => ['description' => '<span class="point">수취인 주소</span>'],
            'delivery_address_detail' => ['description' => '<span class="point">수취인 상세주소</span>'],
            'delivery_address_zipcode' => ['description' => '<span class="point">수취인 우편번호</span>'],
            'delivery_requirement' => ['description' => '<span class="point">배송요청사항</span>'],

            'point_use' => ['description' => '<span class="point">사용할 포인트</span>'],
            'pay_method_id' => ['description' => '<span class="point">결제수단 고유번호</span>'],
            'imp_uid' => ['description' => '<span class="point">아임포트 고유번호</span>'],
            'merchant_uid' => ['description' => '<span class="point">주문번호</span>'],
            'contact' => ['description' => '<span class="point">연락처</span>'],
        ];
    }
}
