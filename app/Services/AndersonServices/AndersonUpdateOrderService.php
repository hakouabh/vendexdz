<?php

namespace App\Services\AndersonServices;

use Illuminate\Support\Facades\Http;

class AndersonUpdateOrderService
{
    protected string $baseUrl = 'https://anderson-ecommerce.ecotrack.dz';
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.eco.anderson_token');
    }

    
     
    public function addUpdate(string $tracking, string $text)
    {
      
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post("{$this->baseUrl}/api/v1/add/maj", [
            'tracking' => $tracking,
            'content'  => mb_substr($text, 0, 255) 
        ]);

        return $response->json();
    }
}