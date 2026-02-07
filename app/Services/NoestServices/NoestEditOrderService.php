<?php

namespace App\Services\NoestServices;

use Illuminate\Support\Facades\Http;

class NoestEditOrderService
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

    public function updateOrder(string $tracking, $updatedData)
    {   
        $formatedData = $this->formatOrder($tracking, $updatedData);
      
        $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post("{$this->baseUrl}/api/public/update/order", $formatedData);
     
        return $response->json();
    }

    public function formatOrder($tracking, $standardOrder)
    {
        return [
            'tracking'   => $tracking,
            'reference'  => $standardOrder->ref,
            'client'     => $standardOrder->name,      // Requis par l'Update API
            'tel'        => $standardOrder->phone,     // Requis par l'Update API
            'tel2'       => $standardOrder->phone2 ?? '',
            'adresse'    => $standardOrder->address,
            'commune'    => $standardOrder->city,
            'wilaya'     => (int) $standardOrder->wilaya, // Doit être un entier (1-58)
            'montant'    => (float) $standardOrder->total_price,
            'product'    => $standardOrder->product_name, // Requis par l'Update API
            'type'       => 1, 
            'stop_desk'  => (int) $standardOrder->delivery_type,
        ];
    }
}