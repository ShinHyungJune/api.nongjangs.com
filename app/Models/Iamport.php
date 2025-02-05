<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Iamport extends Model
{
    use HasFactory;

    // 결제취소
    public static function cancel($accessToken, $imp_uid, $amount = 0, $message = "결제실패")
    {
        return Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => $accessToken
        ])->post("http://api.iamport.kr/payments/cancel", [
            "reason" => $message,
            "imp_uid" => $imp_uid,
            "amount" => $amount
        ])->json();
    }

    // 아임포트 결제요청 권한 얻기
    public static function getAccessToken()
    {
        $response = Http::withHeaders([
            "Content-Type" => "application/json"
        ])->post("http://api.iamport.kr/users/getToken", [
            "imp_key" => config("iamport.key"),
            "imp_secret" => config("iamport.secret"),
        ])->json();

        return $response["response"]["access_token"];
    }

    // 아임포트 주문조회
    public static function getOrder($accessToken, $imp_uid)
    {
        return Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => $accessToken
        ])->get("http://api.iamport.kr/payments/{$imp_uid}", [
            "imp_key" => config("iamport.key"),
            "imp_secret" => config("iamport.secret"),
        ])->json()["response"];
    }

    // 영수증 조회 (이건 현금영수증이 아니라 그냥 결제영수증인듯)
    public static function getBill($accessToken, $imp_uid)
    {
        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => $accessToken
        ])->get("http://api.iamport.kr/payments/{$imp_uid}", [
            "imp_key" => config("iamport.key"),
            "imp_secret" => config("iamport.secret"),
        ])->json()["response"];

        if($response)
            return $response['receipt_url'];

        return null;
    }

    // 현금영수증 발급여부
    public static function checkReceipt($accessToken, $imp_uid)
    {
        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => $accessToken
        ])->get("http://api.iamport.kr/receipts/{$imp_uid}", [
            "imp_key" => config("iamport.key"),
            "imp_secret" => config("iamport.secret"),
        ])->json();

        if($response['code'] == -1)
            return false;

        return true;
    }

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
