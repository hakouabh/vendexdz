<?php

namespace App\Services\AndersonServices;

use Illuminate\Support\Facades\Http;

class AndersonDeleteOrderService
{
    protected string $baseUrl = 'https://anderson-ecommerce.ecotrack.dz';
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.eco.anderson_token');
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