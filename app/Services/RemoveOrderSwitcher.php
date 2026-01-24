<?php

namespace App\Services;
use App\Services\AndersonServices\AndersonDeleteOrderService;
use App\Services\ZRServices\ZRDeleteOrderService;
use App\Models\installedApps;

class RemoveOrderSwitcher
{
    /**
     * Finalize and ship an order (Request Collection)
     */
    public function validate($order)
    {   
        $service = $this->resolveDeleteService($order->app_id, $order->sid);
        return $service->RemoveOrder($order->tracking);
    }

    protected function resolveDeleteService($id, $sid)
    {
        $installedApp = installedApps::where('sid', $sid)->where('app_id', $id)->first();
        return match ((int)$id) {
            1001 => new AndersonDeleteOrderService($installedApp->token),
            1010 => new ZRDeleteOrderService($installedApp->token),
            default => throw new \Exception("Carrier [{$id}] not found."),
        };
    }
}