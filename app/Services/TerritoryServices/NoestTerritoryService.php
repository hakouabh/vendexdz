<?php

namespace App\Services\TerritoryServices;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\installedApps;

class NoestTerritoryService
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

    public function getEverythingCached()
    {
        return Cache::remember('noest_full_data', 86400, function () {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/api/public/get/communes");

            if (!$response->successful()) {
                return ['communes' => []];
            }

            $data = collect($response->json());
            $groupedCommunes = $data->groupBy('wilaya_id')->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'name'           => $item['nom'],
                        'wilaya_id'      => (int) $item['wilaya_id'],
                        'hasPickupPoint' => 0,
                        'zip_code'       => $item['code_postal'] ?? null,
                    ];
                })->values();
            });

            return [
                'communes' => $groupedCommunes->toArray(),
            ];
        });
    }
}