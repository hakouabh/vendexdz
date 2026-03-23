<?php

namespace App\Services\TerritoryFeesServices;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\installedApps;

class EcoTrackTerritoryFeesService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct($installedApp)
    {
        $this->baseUrl = $installedApp->supportedApp->base_url;
        $this->apiKey = $installedApp->token;
    }

    public function getFeesCached()
    {
        return Cache::remember('ecotrack_fees_'. $this->apiKey, 86400, function () {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/api/v1/get/fees");

            if (!$response->successful()) {
                return [];
            }

            $data = collect($response->json()['livraison']);
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