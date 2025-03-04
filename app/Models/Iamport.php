<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Iamport extends Model
{
    use HasFactory;

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

    // 아임포트 주문조회
    public static function getOrder($paymentId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'PortOne ' . config('iamport.secret'),
            'Content-Type' => 'application/json',
        ])->withoutVerifying()
            ->get("https://api.portone.io/payments/{$paymentId}", [

        ]);

        $result = $response->json();

        if(!isset($result['status']))
            return [
                'message' => "주문정보조회에 실패하였습니다.",
                'success' => false,
            ];

        return [
            'success' => true,
            'data' => $result
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
    public function payByBillingKey($order, $card, $user){
        $response = Http::withHeaders([
            'Authorization' => 'PortOne ' . config('iamport.secret'),
            'Content-Type' => 'application/json',
        ])->withoutVerifying()
            ->post('https://api.portone.io/payments/'.$order->payment_id.'/billing-key', [
                'storeId' => config('iamport.store_id'),
                'channelKey' => $order->payMethod->channel_key,
                'customer' => [
                    'id' => (string) $user->id,
                    'email' => $user->email,
                    'name' => ['full' => $user->nickname],
                    'phoneNumber' => $user->contact,
                ],
                'amount' => [
                    'total' => $order->price
                ],
                'currency' => 'KRW',
                'orderName' => $order->format_products,
                'billingKey' => $card->billing_key,
                'noticeUrls' => [
                    config('app.url').'/api/orders/complete/webhook',
                ]
            ]);



        $result = $response->json();

        if(!isset($result['payment']['pgTxId'])) {
            Log::notice($order->payMethod->channel_key);
            Log::notice($card->billing_key);
            Log::notice($result);

            return [
                'message' => isset($result['message']) ? $result['message'] : '결제에 실패하였습니다.',
                'success' => false,
            ];
        }

        return [
            'success' => true,
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
