<?php

namespace App\Services\AndersonServices;

use App\Models\Order;
use App\Models\installedApps;
use Illuminate\Support\Facades\Http;

class AndersonEditOrderService
{
    protected string $baseUrl = 'https://anderson-ecommerce.ecotrack.dz';

    
    public function updateOrder(string $tracking, $updatedData)
    {   
    // 1. Préparer les données avec les bons noms de champs
        $order = Order::where('tracking',$tracking)->first();
        $apikey = installedApps::where('sid',$order->sid)->where('app_id', 1001)->first()->token;
        $queryParams = $this->formatOrder($tracking, $updatedData);
      
    // 2. Construire l'URL avec les paramètres (Query String)
    // L'API attend : /update/order?tracking=XXX&client=YYY...
        $url = "{$this->baseUrl}/api/v1/update/order?" . http_build_query($queryParams);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ])->post($url);
     
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