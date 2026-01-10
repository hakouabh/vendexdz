<?php

namespace App\Services\ZRServices;

use Illuminate\Support\Facades\Http;

class ZRUpdateOrderService
{
    protected string $baseUrl = 'https://app.zrexpress.app';
    protected string $apiKey;
    protected string $tenantId;

    public function __construct()
    {
        $this->apiKey   = config('services.zr.api_key');
        $this->tenantId = config('services.zr.tenant_id');
    }

    
     
    public function addUpdate(string $tracking, string $text)
    {
      
        $response = Http::withHeaders([
            'Accept'    => 'application/json',
            'X-Api-Key' => $this->apiKey,
            'X-Tenant'  => $this->tenantId,
        ])->post("{$this->baseUrl}/api/v1/add/maj", [
            'tracking' => $tracking,
            'content'  => mb_substr($text, 0, 255) 
        ]);

        return $response->json();
    }
}