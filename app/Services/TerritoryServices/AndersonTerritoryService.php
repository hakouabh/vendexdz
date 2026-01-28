<?php

namespace App\Services\TerritoryServices;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\InstalledApps;

class AndersonTerritoryService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct($installedApp)
    {
        $this->baseUrl = $installedApp->supportedApp->base_url;
        $this->apiKey = $installedApp->token;
    }

    public function getEverythingCached()
    {
        return Cache::remember('ecotrack_full_data', 86400, function () {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/api/v1/get/communes");

            if (!$response->successful()) {
                return ['communes' => []];
            }

            $data = collect($response->json());
            $groupedCommunes = $data->groupBy('wilaya_id')->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'name'           => $item['nom'],
                        'wilaya_id'      => (int) $item['wilaya_id'],
                        'hasPickupPoint' => (bool) ($item['has_stop_desk'] == 1),
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