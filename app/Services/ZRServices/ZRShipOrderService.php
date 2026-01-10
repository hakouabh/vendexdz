<?php

namespace App\Services\ZRServices;

use Illuminate\Support\Facades\Http;
use App\Models\Order;

class ZRShipOrderService
{
    protected string $baseUrl = 'https://api.zrexpress.app/api/v1'; 
    protected string $apiKey;
    protected string $tenantId;

    public function __construct()
    {
        $this->apiKey   = config('services.zr.api_key');
        $this->tenantId = config('services.zr.tenant_id');
    }

    /**
    
     */
    public function validateAndShip(string $tracking)
    {

        $order = Order::where('tracking', $tracking)->first();

        if (!$order || !$order->custom_id) {
            return [
                'success' => false,
                'message' => 'Parcel ID (custom_id) not found for this tracking number.'
            ];
        }

        $parcelId = $order->custom_id;
        

        $response = Http::withHeaders([
            'Accept'       => 'application/json',
            'X-Api-Key'    => $this->apiKey,
            'X-Tenant'     => $this->tenantId,
        ])->patch("{$this->baseUrl}/parcels/{$parcelId}/state", [
            "parcelId"   => $parcelId,
            "newStateId" => '8a948c66-1ab7-4433-aeb0-94219125d134',
            "comment"    => "Order validated from system"
        ]);
         
        $data = $response->json();
        dd( $data);
        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Order state updated to: ' . ($data['newStateName'] ?? 'Ready to ship'),
                'data'    => $data
            ];
        }

        return [
            'success' => false,
            'message' => $data['detail'] ?? 'Failed to update parcel state.',
            'errors'  => $data
        ];
    }
}