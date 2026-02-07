<?php

namespace App\Services\NoestServices;

use Illuminate\Support\Facades\Http;

class NoestShipOrderService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $userGuid;

    public function __construct($installedApp)
    {
        $this->baseUrl = $installedApp->supportedApp->base_url;
        $this->apiKey = $installedApp->token;
        $this->userGuid = $installedApp->key;
    }

    
    public function validateAndShip(string $tracking, int $askCollection = 1)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post("{$this->baseUrl}/api/public/valid/order", [
            'user_guid' => $this->userGuid,
            'tracking' => $tracking
        ]);

        return $response->json();
    }
}