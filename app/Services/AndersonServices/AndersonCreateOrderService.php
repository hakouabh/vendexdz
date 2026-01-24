<?php

namespace App\Services\AndersonServices;

use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderInconfirmation;
use App\Models\OrderWaiting;


class AndersonCreateOrderService
{
    protected string $baseUrl = 'https://anderson-ecommerce.ecotrack.dz';
    protected string $apiKey;

    public function __construct($token)
    {
        $this->apiKey = $token;
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
    ])->post("{$this->baseUrl}/api/v1/create/orders", ['orders' => $formattedOrders]);

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
            "nom_client"  => $standardOrder->name,
            "telephone"   => $standardOrder->phone,
            "telephone_2" => $standardOrder->phone2 ?? "", // Added telephone_2
            "adresse"     => $standardOrder->address,
            "commune"     => $standardOrder->city,
            "code_wilaya" => (string) $standardOrder->wilaya,
            "montant"     => (string) $standardOrder->total_price,
            "produit"     => $standardOrder->product_name,
            "stop_desk"   => (int) $standardOrder->delivery_type,
            "weight"      => "1",
            "type"        => "1",
        ];
    }
}