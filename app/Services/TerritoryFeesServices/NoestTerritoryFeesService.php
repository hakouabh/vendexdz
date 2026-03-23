<?php

namespace App\Services\TerritoryFeesServices;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\installedApps;

class NoestTerritoryFeesService
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

    public function getFeesCached()
    {
        return Cache::remember('noest_fees_'. $this->apiKey, 86400, function () {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/api/public/fees");
            
            if (!$response->successful()) {
                return [];
            }
            $data = collect($response->json()['tarifs']['delivery']);
            $groupedWilaya = $data->map(function ($item) {
                return [
                    'wilaya_id'      => (int) $item['wilaya_id'],
                    'fees' => $item['tarif'],
                    'fees_stopdesk' => $item['tarif_stopdesk'] ?? null
                ];
            });
            return $groupedWilaya->toArray();
        });
    }
}