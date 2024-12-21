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

        switch ($method){
            case 'index':
                return [
                    'started_at' => 'nullable|string|max:500',
                    'finished_at' => 'nullable|string|max:500',
                    'word' => 'nullable|string|max:500',
                ];

            case 'store':
                return [
                    'presets' => 'required|array|max:100',
                ];

            case 'update':
                $data = [
                    'agree_open' => ['nullable','boolean'],
                    'buyer_email' => ['required','email','max:500'],
                    'buyer_name' => ['required','string','max:500'],
                    'buyer_contact' => ['required','string','max:500'],
                    'buyer_address' => ['required','string','max:500'],
                    'buyer_address_detail' => ['required','string','max:500'],
                    'buyer_address_zipcode' => ['required','string','max:500'],

                    'delivery_name' => ['required','string','max:500'],
                    'delivery_contact' => ['required','string','max:500'],
                    'delivery_address' => ['required','string','max:500'],
                    'delivery_address_detail' => ['required','string','max:500'],
                    'delivery_address_zipcode' => ['required','string','max:500'],
                    'delivery_requirement' => ['nullable','string','max:500'],

                    'type_delivery' => ['required','integer'],
                    'point_use' => ['required','integer'],
                    'coupon_id' => ['nullable','integer'],

                    'pay_method_id' => ['required', 'integer'],
                ];

                if($this->input('need_tax')){
                    $data = array_merge($data, [
                        'tax_business_number' => ['required','string','max:500'],
                        'tax_company_title' => ['required','string','max:500'],
                        'tax_company_president' => ['required','string','max:500'],
                        'tax_company_type' => ['required','string','max:500'],
                        'tax_company_category' => ['required','string','max:500'],
                        'tax_email' => ['required','string','max:500'],
                        'tax_name' => ['required','string','max:500'],
                        'tax_contact' => ['required','string','max:500'],
                        'tax_address' => ['required','string','max:500'],
                    ]);
                }

                return $data;

            case 'complete':
                return [
                    "imp_uid" => "required|string|max:50000",
                    "merchant_uid" => "required|string|max:50000",
                ];

            case 'showByGuest':
                return [
                    'merchant_uid' => 'nullable|string|max:500',
                    'buyer_contact' => 'required|string|max:500',
                    'buyer_name' => 'required|string|max:500'
                ];

            case 'bill':
                return [
                    'merchant_uid' => 'required|string|max:500'
                ];

            default:
                return [];
        }
    }

    public function bodyParameters()
    {
        return [
            'agree_open' => ['description' => '<span class="point">주문제품 자사홈페이지 노출 허용여부 (1이면 허용)</span>'],
            'started_at' => ['description' => '<span class="point">시작일자</span>'],
            'finished_at' => ['description' => '<span class="point">종료일자</span>'],
            'word' => ['description' => '<span class="point">검색어</span>'],
            'presets' => ['description' => '<span class="point">상품조합목록</span>'],
            'buyer_name' => ['description' => '<span class="point">주문자 이름</span>'],
            'buyer_contact' => ['description' => '<span class="point">주문자 연락처</span>'],
            'buyer_address' => ['description' => '<span class="point">주문자 주소</span>'],
            'buyer_address_detail' => ['description' => '<span class="point">주문자 상세주소</span>'],
            'buyer_address_zipcode' => ['description' => '<span class="point">주문자 우편번호</span>'],
           
            'delivery_name' => ['description' => '<span class="point">수취인 이름</span>'],
            'delivery_contact' => ['description' => '<span class="point">수취인 연락처</span>'],
            'delivery_address' => ['description' => '<span class="point">수취인 주소</span>'],
            'delivery_address_detail' => ['description' => '<span class="point">수취인 상세주소</span>'],
            'delivery_address_zipcode' => ['description' => '<span class="point">수취인 우편번호</span>'],
            'delivery_requirement' => ['description' => '<span class="point">배송요청사항</span>'],
            'type_delivery' => ['description' => '<span class="point">택배 방법 (1 - 택배 DELIVERY | 2 - 퀵 QUICK | 3 - 직접방문 DIRECT)</span>'],
            'point_use' => ['description' => '<span class="point">사용할 포인트</span>'],
            'coupon_id' => ['description' => '<span class="point">사용할 쿠폰 고유번호</span>'],
            'pay_method_id' => ['description' => '<span class="point">결제수단 고유번호</span>'],
            'imp_uid' => ['description' => '<span class="point">아임포트 고유번호</span>'],
            'merchant_uid' => ['description' => '<span class="point">주문번호</span>'],
            'contact' => ['description' => '<span class="point">연락처</span>'],
        ];
    }
}
