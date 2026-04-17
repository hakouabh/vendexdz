<?php

namespace App\Services\TerritoryServices;

use Illuminate\Support\Facades\Http;

class ZRTerritoryService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $tenantId;

    public function __construct($installedApp)
    {
        $this->baseUrl = $installedApp->supportedApp->base_url;
        $this->apiKey = $installedApp->token;
        $this->tenantId = $installedApp->key;
    }

    
    public function getEverythingCached()
    {
        return cache()->remember('ZRExpressTerritories_'.$this->tenantId, 86400, function () {
            $response = Http::withHeaders([
                'Accept'    => 'application/json',
                'X-Api-Key' => $this->apiKey,
                'X-Tenant'  => $this->tenantId,
            ])->post("{$this->baseUrl}/territories/search",[
                "pageNumber" =>  1,
                "pageSize" =>  5000,
                "orderBy" =>  [
                    "code asc"
                ]
            ]);
            if (!$response->successful()) {

                return [];
            }
            $items = collect($response->json()['items']);
            $allCommunes = collect();
            $allWilayas = collect();
            $allWilayas = $allWilayas->merge($items->where('level', 'wilaya'));
            $allCommunes = $allCommunes->merge($items->where('level', 'commune'));
            return [
                'wilayas'  => $allWilayas->keyBy(fn($i) => (int)$i['code'])->toArray(),
                'communes' => $allCommunes->groupBy('parentId')->toArray(),
            ];
        });
    }

    public function getFeesCached()
    {
        $response = Http::withHeaders([
            'Accept'    => 'application/json',
            'X-Api-Key' => $this->apiKey,
            'X-Tenant'  => $this->tenantId,
        ])->get("{$this->baseUrl}/delivery-pricing/rates",[
            "pageNumber" =>  1,
            "pageSize" =>  5000,
            "orderBy" =>  [
                "code asc"
            ]
        ]);
        if (!$response->successful()) {

            return [];
        }
        $data = collect($response->json()['rates']);        
        $groupedWilaya = collect($data)
            ->filter(function ($item) {
                return ($item['toTerritoryLevel'] ?? null) === 'wilaya';
            })
            ->map(function ($item) {

                $home = collect($item['deliveryPrices'])
                    ->firstWhere('deliveryType', 'home');

                $pickup = collect($item['deliveryPrices'])
                    ->firstWhere('deliveryType', 'pickup-point');

                return [
                    'wilaya_id' => $item['toTerritoryCode'],
                    'fees' => $home['price'] ?? 0,
                    'fees_stopdesk' => $pickup['price'] ?? 0,
                ];
            })
            ->unique('wilaya_id')
            ->sortByDesc('wilaya_id')
            ->values();
        return $groupedWilaya->toArray();
    }
}