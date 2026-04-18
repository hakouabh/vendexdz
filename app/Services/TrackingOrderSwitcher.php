<?php

namespace App\Services;

use App\Services\WorldExpressServices\WorldExpressTrackingService;

use App\Models\installedApps;

class TrackingOrderSwitcher
{
    public function dispatch($orders)
    {
        $first = $orders->first();

        $service = $this->resolveService($first->app_id, $first->sid);

        $trackings = $orders->pluck('tracking')->toArray();

        return $service->trackOrders($trackings);
    }

    protected function resolveService($id, $sid)
    {
        $installedApp = installedApps::where('sid', $sid)
            ->where('app_id', $id)
            ->first();

        return match ((int)$id) {
            1001, 1002, 1003 => new WorldExpressTrackingService($installedApp),
            // 1015 => new NoestStatusService($installedApp),
            default => throw new \Exception("Carrier Service ID [{$id}] not found in Switcher."),
        };
    }
}
