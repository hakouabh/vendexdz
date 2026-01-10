<?php

namespace App\Services\ZRServices;

use Illuminate\Support\Facades\Http;
use App\Models\Order;

class ZRDeleteOrderService
{
    protected string $baseUrl = 'https://api.zrexpress.app/api/v1';
    protected string $apiKey;
    protected string $tenantId;

    public function __construct()
    {
        $this->apiKey   = config('services.zr.api_key');
        $this->tenantId = config('services.zr.tenant_id');
    }

    
    public function RemoveOrder(string $tracking)
    {

        $order = Order::where('tracking', $tracking)->first();

        if (!$order || !$order->custom_id) {
            return [
                'success' => false,
                'message' => 'Parcel ID (custom_id) not found in local database.'
            ];
        }

        $parcelId = $order->custom_id;

        $response = Http::withHeaders([
            'Accept'    => 'application/json',
            'X-Api-Key' => $this->apiKey,
            'X-Tenant'  => $this->tenantId,
        ])->delete("{$this->baseUrl}/parcels/{$parcelId}");

    
        if ($response->status() === 204 || $response->successful()) {
            return [
                'success' => true,
                'message' => 'Parcel deleted successfully from ZR Express.'
            ];
        }

      
        return [
            'success' => false,
            'status'  => $response->status(),
            'message' => $response->json()['detail'] ?? 'Failed to delete parcel.'
        ];
    }
}