<?php

namespace App\Services\ZRServices;

use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderInconfirmation;
use App\Models\OrderWaiting;

class ZRCreateOrderService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $tenantId;
    protected $installedApp;

    public function __construct($installedApp)
    {
        $this->installedApp = $installedApp;
        $this->baseUrl = $installedApp->supportedApp->base_url;
        $this->apiKey = $installedApp->token;
        $this->tenantId = $installedApp->key;
    }

    public function sendOrders($standardOrders)
    { 
        
        $parcels = [];
        foreach ($standardOrders as $order) {
            $parcels[] = $this->formatOrder($order);
        }

        $response = Http::withHeaders([
            'Accept'    => 'application/json',
            'X-Api-Key' => $this->apiKey,
            'X-Tenant'  => $this->tenantId,
        ])->post("{$this->baseUrl}/parcels/bulk", ['parcels' => $parcels]);

        $data = $response->json();
      
        return $data;
    }

    protected function updateOrdersInDatabase($successes)
    {
        foreach ($successes as $item) {
         
            $ref = $item['externalId'] ?? null;
            $trackingNumber = $item['trackingNumber'] ?? null;
            $parcelId = $item['parcelId'] ?? null;
            if ($ref && $trackingNumber) {
                $numericId = str_replace('VN-', '', $ref); 
                
                Order::where('oid', $numericId)->update([
                    'tracking' => $trackingNumber, 
                    'custom_id'=> $parcelId
                ]);

                OrderInconfirmation::where('oid', $numericId)->delete();
                OrderWaiting::create(['oid'=>$numericId,'asid'=>1]);
            }
        }
    }
    
    public function formatOrder($standardOrder)
    {
        $territoryService = new \App\Services\TerritoryServices\ZRTerritoryService($this->installedApp);
        $territoryData = $territoryService->getEverythingCached();

        $wilayaCode = (int)$standardOrder->wilaya;
        $zrWilaya = $territoryData['wilayas'][$wilayaCode] ?? null;

        if (!$zrWilaya) {
            throw new \Exception("ZR Error: Wilaya code {$wilayaCode} not found.");
        }

        $wilayaUuid = $zrWilaya['id'];
        $communesInWilaya = collect($territoryData['communes'][$wilayaUuid] ?? []);
        $zrCommune = $communesInWilaya->firstWhere('name', $standardOrder->city);

        if (!$zrCommune) {
            throw new \Exception("ZR Error: Commune '{$standardOrder->city}' not found in {$zrWilaya['name']}.");
        }

        return [
            "customer" => [
                "customerId"=> "5c809fd6-dfca-4f72-a88a-10dd333339de",
                "name" => $standardOrder->name,
                "phone" => [
                    "number1" => $this->formatPhone($standardOrder->phone),
                    "number2" => $standardOrder->phone2 ? $this->formatPhone($standardOrder->phone2) : null
                ]
            ],
            "deliveryAddress" => [
                "street"             => $standardOrder->address ?: "N/A",
                "city"               => $zrWilaya['name'], 
                "district"           => $zrCommune['name'],
                "country"            => "algeria",
                "cityTerritoryId"    => $zrWilaya['id'], 
                "districtTerritoryId" => $zrCommune['id']
            ],
            "orderedProducts" => [
                [
                   
                    // "productSku"  => "CHOC-LINDT-001",
                    "productName" => $standardOrder->product_name,
                    "unitPrice"   => (double) $standardOrder->total_price,
                    "quantity"    => (int) ($standardOrder->quantity ?? 1),
                    "stockType"   => "none" 
                ]
            ],
            "amount"       => (double) $standardOrder->total_price,
            "description"  => $standardOrder->product_name,
            "deliveryType" => $standardOrder->delivery_type == 1 ? "pickup-point" : "home",
            "externalId"   => $standardOrder->ref
        ];
    }

    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            return '+213' . substr($phone, 1);
        }
        return '+' . $phone;
    }
}