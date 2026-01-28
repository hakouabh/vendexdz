<?php

namespace App\Services;

use App\Services\AndersonServices\AndersonEditOrderService;
use App\Services\ZRServices\ZREditOrderService;
use App\Models\installedApps;

class EditeOrderSwitcher
{
    /**
     * Now accepts a single standard object OR an array of standard objects
     */
    public function dispatch($order, $orders) 
    {   
        
        // 1. Resolve service
        $service = $this->resolveService($order->app_id, $order->sid);
        
        $result = $service->updateOrder($order->tracking, $orders);
          
        return $result;
    }

    protected function resolveService($id, $sid)
    {
        $installedApp = installedApps::where('sid', $sid)->where('app_id', $id)->first();
        return match ((int)$id) {
            1001 => new AndersonEditOrderService($installedApp),
            1002 => new AndersonEditOrderService($installedApp),
            1003 => new AndersonEditOrderService($installedApp),
            1010 => new ZREditOrderService($installedApp->token),
            default => throw new \Exception("Carrier Service ID [{$id}] not found in Switcher."),
        };
    }
}