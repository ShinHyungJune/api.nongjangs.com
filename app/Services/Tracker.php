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

        $this->client = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'TRACKQL-API-KEY ' . $this->clientId . ':' . $this->secret
        ]);
    }

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

        $response = $this->client->post($this->domain, [
            'query' => $query,
            'variables' => [
                'carrierId' => $carrierId,
                'trackingNumber' => $trackingNumber
            ]
        ]);

        dd($response);
    }
}