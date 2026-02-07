<?php

namespace App\Services\NoestServices;

use Illuminate\Support\Facades\Http;

class NoestDeleteOrderService
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
     
    public function RemoveOrder(string $tracking)
    {
      
        $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post("{$this->baseUrl}/api/public/delete/order", [
        'user_guid' => $this->userGuid,
        'tracking' => $tracking
        ]);

        return $response->json();
    }
}