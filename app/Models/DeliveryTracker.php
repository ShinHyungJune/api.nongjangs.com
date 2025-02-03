<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class DeliveryTracker extends Model
{
    use HasFactory;

    protected $clientId = "6im9psfll4m0u7k3vedk870ufj";
    protected $secret = "17a92jve0gin53jl4bmimu6o5fk8rftnaen10v0mokagdo2s6th1";

    public function monitor($deliveryCompanyId, $number, $callbackUrl = null)
    {
        $response = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "TRACKQL-API-KEY {$this->clientId}:{$this->secret}",
        ])->post('https://apis.tracker.delivery/graphql', [
            'query' => "mutation RegisterTrackWebhook(\n  \$input: RegisterTrackWebhookInput!\n) {\n  registerTrackWebhook(input: \$input)\n}",
            'variables' => [
                'input' => [
                    'carrierId' => $deliveryCompanyId,
                    'trackingNumber' => $number,
                    'callbackUrl' => $callbackUrl,
                    'expirationTime' => Carbon::now()->addDays(2),
                ],
            ],
        ]);

        $result = $response->json(); // JSON 응답을 배열로 변환

        if(isset($result['errors']))
            return [
                'success' => 0,
                'message' => $result['errors'][0]['message']
            ];

        return [
            'success' => 1,
        ];
    }

    public function track($deliveryCompanyId, $number)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "TRACKQL-API-KEY {$this->clientId}:{$this->secret}",
        ])->post('https://apis.tracker.delivery/graphql', [
            'query' => "query Track(\n  \$carrierId: ID!,\n  \$trackingNumber: String!\n) {\n  track(\n    carrierId: \$carrierId,\n    trackingNumber: \$trackingNumber\n  ) {\n    lastEvent {\n      time\n      status {\n        code\n        name\n      }\n      description\n    }\n    events(last: 10) {\n      edges {\n        node {\n          time\n          status {\n            code\n            name\n          }\n          description\n        }\n      }\n    }\n  }\n}",
            'variables' => [
                'carrierId' => $deliveryCompanyId,
                'trackingNumber' => $number,
            ],
        ]);

        $result = $response->json(); // JSON 응답을 배열로 변환

        if(isset($result['errors']))
            return [
                'success' => 0,
                'message' => $result['errors'][0]['message']
            ];

        return [
            'data' => $result['data']['track']['events']['edges'],
            'success' => 1,
        ];
    }

}
