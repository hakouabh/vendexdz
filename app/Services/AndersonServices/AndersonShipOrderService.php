<?php

namespace App\Services\AndersonServices;

use Illuminate\Support\Facades\Http;

class AndersonShipOrderService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct($installedApp)
    {
        $this->baseUrl = $installedApp->supportedApp->base_url;
        $this->apiKey = $installedApp->token;
    }

    
    public function validateAndShip(string $tracking, int $askCollection = 1)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post("{$this->baseUrl}/api/v1/valid/order?tracking={$tracking}&ask_collection={$askCollection}");

        return $response->json();
    }
}