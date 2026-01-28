<?php

namespace App\Services\AndersonServices;

use Illuminate\Support\Facades\Http;

class AndersonDeleteOrderService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct($installedApp)
    {
        $this->baseUrl = $installedApp->supportedApp->base_url;
        $this->apiKey = $installedApp->token;
    }
     
    public function RemoveOrder(string $tracking)
    {
      
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->delete("{$this->baseUrl}/api/v1/delete/order?tracking={$tracking}");

        return $response->json();
    }
}