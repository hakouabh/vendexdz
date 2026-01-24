<?php

namespace App\Services\AndersonServices;

use Illuminate\Support\Facades\Http;

class AndersonReturnOrderService
{
    protected string $baseUrl = 'https://anderson-ecommerce.ecotrack.dz';
    protected string $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = config('services.eco.anderson_token');
    }

    
     
    public function addUpdate(string $tracking, string $text)
    {
      
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post("{$this->baseUrl}/api/v1/ask/for/order/return", [
            'tracking' => $tracking,
        ]);

        return $response->json();
    }
}