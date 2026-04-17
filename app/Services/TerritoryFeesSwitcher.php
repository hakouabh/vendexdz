<?php

namespace App\Services;

use App\Services\TerritoryFeesServices\EcoTrackTerritoryFeesService;
use App\Services\TerritoryFeesServices\NoestTerritoryFeesService;
use App\Services\TerritoryServices\ZRTerritoryService;

use App\Models\installedApps;

class TerritoryFeesSwitcher
{
    public function getFees($installedApp)
    {
        $service = $this->resolveService($installedApp);
        return $service->getFeesCached();
    }

    protected function resolveService($installedApp)
    {
        return match ((int)$installedApp->app_id) {
            1001, 1002, 1003 => new EcoTrackTerritoryFeesService($installedApp),
            1015 => new NoestTerritoryFeesService($installedApp),
            1010 => new ZRTerritoryService($installedApp),
            default => throw new \Exception("Carrier Service ID [{$installedApp->app_id}] not found in Switcher."),
        };
    }
}
