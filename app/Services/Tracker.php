<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Tracker
{
    protected $clientId;
    protected $secret;
    protected $client;
    protected $domain = 'https://apis.tracker.delivery/graphql';

    public function __construct()
    {
        $this->clientId = config('services.tracker.client_id');
        $this->secret = config('services.tracker.secret');

        $this->client = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'TRACKQL-API-KEY ' . $this->clientId . ':' . $this->secret
        ]);
    }

    /**
     * 운송장번호와 택배사 고유번호로 배송상태를 조회한다
     * 
     * @param string $trackingNumber 운송장번호
     * @param string $carrierId 택배사 고유번호
     * @return array 배송상태 정보
     */
    public function getState($trackingNumber, $carrierId)
    {
        $query = "query Track(\$carrierId: ID!, \$trackingNumber: String!) {
            track(carrierId: \$carrierId, trackingNumber: \$trackingNumber) {
                lastEvent {
                    time
                    status {
                        code
                    }
                }
            }
        }";

        try {
            $response = $this->client->post($this->domain, [
                'query' => $query,
                'variables' => [
                    'carrierId' => $carrierId,
                    'trackingNumber' => $trackingNumber
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']['track'])) {
                    $lastEvent = $data['data']['track']['lastEvent'];

                    return [
                        'success' => true,
                        'message' => '',
                        'data' => [
                            'finish' => $lastEvent && $lastEvent['status']['code'] === 'DELIVERED',
                        ],
                    ];
                }
            }

            $errorMessage = '배송상태 조회에 실패했습니다.';

            \Illuminate\Support\Facades\Log::info("배송상태 조회 실패: {$trackingNumber}, {$carrierId}");

            return [
                'success' => false,
                'message' => $errorMessage
            ];
        } catch (\Exception $e) {
            $errorMessage = '배송상태 조회 중 오류가 발생했습니다: ' . $e->getMessage();
            \Illuminate\Support\Facades\Log::info("배송상태 조회 예외 발생: {$trackingNumber}, {$carrierId}, {$e->getMessage()}");
            return [
                'success' => false,
                'message' => $errorMessage
            ];
        }
    }

    /**
     * 택배사 목록을 조회한다
     * 
     * @return array 택배사 목록
     */
    public function getCarriers($after = null)
    {
        $query = 'query CarrierList($countryCode: String, $after: String) {
        carriers(
            countryCode: $countryCode,
            first: 100,
            after: $after
        ) {
            pageInfo {
                hasNextPage
                endCursor
            }
            edges {
                node {
                    id
                    name
                }
            }
        }
    }';

        try {
            $response = $this->client->post($this->domain, [
                'query' => $query,
                'variables' => [
                    'countryCode' => 'KR',
                    'after' => $after
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']['carriers'])) {
                    $carriers = $data['data']['carriers'];
                    $formattedCarriers = [];

                    // edges.node 구조에서 데이터 추출
                    foreach ($carriers['edges'] as $edge) {
                        $carrier = $edge['node'];
                        $formattedCarriers[] = [
                            'title' => $carrier['name'],
                            'code' => $carrier['id']
                        ];
                    }

                    return [
                        'success' => true,
                        'message' => '',
                        'data' => $formattedCarriers,
                        'pagination' => [
                            'hasNextPage' => $carriers['pageInfo']['hasNextPage'],
                            'endCursor' => $carriers['pageInfo']['endCursor']
                        ]
                    ];
                }
            }

            $errorMessage = '택배사 목록 조회에 실패했습니다.';
            \Illuminate\Support\Facades\Log::info("택배사 목록 조회 실패");

            return [
                'success' => false,
                'message' => $errorMessage
            ];
        } catch (\Exception $e) {
            $errorMessage = '택배사 목록 조회 중 오류가 발생했습니다: ' . $e->getMessage();
            \Illuminate\Support\Facades\Log::info("택배사 목록 조회 예외 발생: {$e->getMessage()}");
            return [
                'success' => false,
                'message' => $errorMessage
            ];
        }
    }

}
