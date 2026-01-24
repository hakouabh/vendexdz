<?php

namespace App\Services;
use App\Services\AndersonServices\AndersonShipOrderService;
use App\Services\ZRServices\ZRShipOrderService;
use App\Models\installedApps;

class ShipOrderSwitcher
{
    /**
     * Finalize and ship an order (Request Collection)
     */
    public function validate($order)
    { 
        $service = $this->resolveShipService($order->app_id, $order->sid);
        return $service->validateAndShip($order->tracking);
    }

    protected function resolveShipService($id, $sid)
    {
        $installedApp = installedApps::where('sid', $sid)->where('app_id', $id)->first();
        return match ((int)$id) {
            1001 => new AndersonShipOrderService($installedApp->token),
            1010 => new ZRShipOrderService($installedApp->token),
            default => throw new \Exception("Carrier [{$id}] not found."),
        };
    }
}