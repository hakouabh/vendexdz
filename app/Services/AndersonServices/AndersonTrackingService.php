<?php

namespace App\Services\AndersonServices;

use Illuminate\Support\Facades\Http;
use App\Models\installedApps;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class AndersonTrackingService
{
    protected string $baseUrl = 'https://anderson-ecommerce.ecotrack.dz';

    public function TrackThem()
    {
      
        $apps = installedApps::where('app_id', 1001)->get();

        foreach ($apps as $app) {
            $this->syncAllPages($app->token);
        }
      
    }

    public function syncAllPages(string $token )
    {
         
        $nextPageUrl = "{$this->baseUrl}/api/v1/get/orders";

        while ($nextPageUrl) {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])->get($nextPageUrl);

            if (!$response->successful()) {
                Log::error("Anderson API Error: " . $response->body());
                break; 
            }

            $rawData = $response->json();
            $ordersData = $rawData['data'] ?? [];
            
           dd($rawData);
            foreach ($ordersData as $remoteOrder) {
                $this->updateInternalOrder($remoteOrder);
            }

            $nextPageUrl = $rawData['next_page_url'] ?? null;
            usleep(200000); 
        }
    }

    protected function updateInternalOrder(array $remoteOrder )
    {
        $trackingCode = $remoteOrder['tracking'];
        $internalStatus = $this->mapStatus($remoteOrder['status']);
        $order = Order::where('tracking', $trackingCode)->first();
         
        if ($order) {
            $status = $this->mapStatus($remoteOrder['status']);

            $order->Indelivery()->updateOrCreate(
                ['oid' => $order->oid], 
                [
                    'ssid' => $internalStatus,
                ]
            );

            // اختياري: إذا أردت تسجيل لوق (Log) لكل تحديث جديد
            /*
            $order->logs()->create([
                'content' => "Status updated to: " . $status,
                'user_id' => 0 // System update
            ]);
            */
        }
        
    }

    private function mapStatus($status)
    {
        $map = [
                'en_ramassage'           =>  '1',
 	            'en_preparation_stock'   =>  '2',
 	            'vers_hub'               =>  '3',
 	            'en_hub'                 =>  '4',
 	            'vers_wilaya'            =>  '5',
 	            'en_preparation'         =>  '6',
 	            'en_livraison'           =>  '7',
 	            'suspendu'               =>  '8',
 	            'livre_non_encaisse'     =>  '9',
  	            'encaisse_non_paye'      =>  '10',
 	            'paiements_prets'        =>  '11',
 	            'paye_et_archive'        =>  '12',
 	            'retour_chez_livreur'    =>  '13',
 	            'retour_transit_entrepot'=>  '14',
 	            'retour_en_traitement'   =>  '15',
 	            'retour_recu'            =>  '16',
 	            'retour_archive'         =>  '17',
 	            'annule'                 =>  '18',

        ];

        return $map[$status] ?? 'unknown';
    }
}