<?php

namespace App\Services\NoestServices;

use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderInconfirmation;
use App\Models\OrderWaiting;

class NoestCreateOrderService
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

    /**
     * Send multiple orders to EcoTrack
     * @param array|\Illuminate\Support\Collection $standardOrders
     */
    public function sendOrders($standardOrders)
    { 
    $formattedOrders = [];
    foreach ($standardOrders as $order) {
        $formattedOrders[] = $this->formatOrder($order);
    }

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $this->apiKey,
    ])->post("{$this->baseUrl}/api/public/create/orders", [
        'user_guid' => $this->userGuid,
        'orders' => $formattedOrders
        ]);
        
        $data = $response->json();

        return $data;
    }

    protected function updateOrdersInDatabase($apiResponse)
    {
    foreach ($apiResponse['results'] as $ref => $details) {
        // Check if the individual order was successful
        if (isset($details['success']) && $details['success']) {
            
            $numericId = str_replace('VN-', '', $ref); 
            
            // Double check: is your column 'tracking' or 'tracking_number'?
            Order::where('oid', $numericId)->update([
                'tracking' => $details['tracking'], // Fixed column name
            ]);
            OrderInconfirmation::where('oid', $numericId)->delete();
            OrderWaiting::create(['oid'=>$numericId,'asid'=>1]);
        }
    }
    }
    
    public function formatOrder($standardOrder)
    {
        return [
            "reference"   => $standardOrder->ref,
            "client"  => $standardOrder->name,
            "phone"   => $standardOrder->phone,
            "adresse"     => $standardOrder->address,
            "commune"     => $standardOrder->city,
            "wilaya_id" => (string) $standardOrder->wilaya,
            "montant"     => (string) $standardOrder->total_price,
            "produit"     => $standardOrder->product_name,
            "stop_desk"   => (int) $standardOrder->delivery_type,
            "poids"      => "1",
            "type_id"        => "1",
        ];
    }
}