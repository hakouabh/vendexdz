<?php

namespace App\Services\WorldExpressServices;

use Illuminate\Support\Facades\Http;

class WorldExpressTrackingService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct($installedApp)
    {
        $this->baseUrl = $installedApp->supportedApp->base_url;
        $this->token = $installedApp->token;
    }

    public function trackOrders(array $trackings)
    {
        $trackingsString = implode(',', $trackings);

        $url = "{$this->baseUrl}/api/v1/get/orders/status";

        $response = Http::timeout(30)->get($url, [
            'api_token' => $this->token,
            'trackings' => $trackingsString,
            'status' => 'all'
        ]);
        $data = $response->json();
        $ordersData = $data['data'] ?? [];

        $results = [];

        foreach ($ordersData as $tracking => $remoteOrder) {
            $results[] = [
                'tracking' => $tracking,
                'internal_status' => $this->mapStatus($remoteOrder['status']),
                'remote_status' => $remoteOrder['status']
            ];
        }
        return $results;
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
            'retour_transit_entrepot' =>  '14',
            'retour_en_traitement'   =>  '15',
            'retour_recu'            =>  '16',
            'retour_archive'         =>  '17',
            'annule'                 =>  '18',
            'prete_a_expedier'       => null
        ];

        return $map[$status] ?? 'unknown';
    }
}
