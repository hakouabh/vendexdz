<?php

namespace App\Services\ZRServices;

use Illuminate\Support\Facades\Http;

class ZRReturnOrderService
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
        ])->post("{$this->baseUrl}/api/v1/ask/for/order/return", [
            'tracking' => $tracking,
        ]);

        return $response->json();
    }
}