<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Iamport extends Model
{
    use HasFactory;

    protected $accessToken;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->accessToken = $this->getAccessToken();
    }

    // 아임포트 결제요청 권한 얻기
    public function getAccessToken()
    {
        $response = Http::withHeaders([
            "Content-Type" => "application/json"
        ])->post("http://api.iamport.kr/users/getToken", [
            "imp_key" => config("iamport.key"),
            "imp_secret" => config("iamport.secret"),
        ])->json();

        return $response["response"]["access_token"];
    }

    // 결제취소
    public static function cancel($paymentId, $amount = 0)
    {
        $response = Http::withHeaders([
            'Authorization' => 'PortOne ' . config('iamport.secret'),
            'Content-Type' => 'application/json',
        ])->withoutVerifying()
            ->post("https://api.portone.io/payments/{$paymentId}/cancel", [
                'amount' => $amount,
                'reason' => '주문취소'
            ]);

        $result = $response->json();

        if(!isset($result['cancellation']))
            return [
                'message' => "주문취소에 실패하였습니다.",
                'success' => false,
            ];

        if(isset($result['message']))
            return [
                'message' => $result['message'],
                'success' => false,
            ];

        return [
            'success' => true,
        ];
    }

    public function getBillingKeyOrder($billingKey){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json',
        ])->withoutVerifying()
            ->get('https://api.iamport.kr/subscribe/customers/'.$billingKey, [

            ]);

        $result = $response->json();

        if(!isset($result['code']) || $result['code'] != 0) {
            return [
                'message' => isset($result['message']) ? $result['message'] : '결제에 실패하였습니다.',
                'success' => false,
            ];
        }

        return [
            'success' => true,
            'data' => $result['response'],
        ];
    }

    // 아임포트 주문조회
    public function getOrder($imp_uid)
    {
        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => $this->accessToken
        ])->get("http://api.iamport.kr/payments/{$imp_uid}", [
            "imp_key" => config("iamport.key"),
            "imp_secret" => config("iamport.secret"),
        ]);

        $result = $response->json();

        if(!isset($result['code']) || $result['code'] != 0) {
            return [
                'message' => isset($result['message']) ? $result['message'] : '결제에 실패하였습니다.',
                'success' => false,
            ];
        }

        return [
            'success' => true,
            'data' => $result['response'],
        ];
    }

    // 빌링키 발급
    public function createBillingKey($data, $user)
    {
        $response = Http::withHeaders([
            'Authorization' => 'PortOne ' . config('iamport.secret'),
            'Content-Type' => 'application/json',
        ])->withoutVerifying()
            ->post('https://api.portone.io/billing-keys', [
                'storeId' => config('iamport.store_id'),
                'channelKey' => config('iamport.channel_key'),
                'customer' => [
                    'id' => (string) $user->id,
                    'email' => $user->email,
                    'name' => ['full' => $user->nickname],
                    'phoneNumber' => $user->contact,
                ],
                'method' => [
                    'card' => [
                        'credential' => [
                            'number' => $data['number'],
                            'expiryYear' => $data['expiry_year'],
                            'expiryMonth' => $data['expiry_month'],
                            'birthOrBusinessRegistrationNumber' => $data['birth_or_business_number'],
                            'passwordTwoDigits' => $data['password'],
                        ],
                    ],
                ],
            ]);

        $result = $response->json();

        if(!isset($result['billingKeyInfo']))
            return [
                'message' => isset($result['pgMessage']) ? $result['pgMessage'] : '카드등록에 실패하였습니다. 카드정보를 확인해주세요.',
                'success' => false,
            ];

        return [
            'success' => true,
            'data' => $result['billingKeyInfo']['billingKey']
        ];
    }

    // 빌링키 결제
    public function payByBillingKey($order, $card){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json',
        ])->withoutVerifying()
            ->post('https://api.iamport.kr/subscribe/payments/again', [
                'customer_uid' => $card->billing_key,
                'merchant_uid' => $order->merchant_uid,
                'name' => '농장스',
                'amount' => $order->price,
                'currency' => 'KRW',
            ]);

        $result = $response->json();

        if(!isset($result['code']) || $result['code'] != 0) {
            return [
                'message' => isset($result['message']) ? $result['message'] : '결제에 실패하였습니다.',
                'success' => false,
            ];
        }

        return [
            'success' => true,
            'data' => $result['response']
        ];
    }

    public function getBillingKeyInformation($billingKey)
    {
        $response = Http::withHeaders([
            'Authorization' => 'PortOne ' . config('iamport.secret'),
            'Content-Type' => 'application/json',
        ])->withoutVerifying()
            ->get('https://api.portone.io/billing-keys/'.$billingKey, [
                'storeId' => config('iamport.store_id'),
                'billingKey' => $billingKey,
            ]);

        return [
            'success' => true
        ];
    }
}
