<?php

namespace App\Services;

use App\Services\AndersonServices\AndersonCreateOrderService;
use App\Services\ZRServices\ZRCreateOrderService;
use App\Models\installedApps;

class ShippingSwitcher
{
    /**
     * Now accepts a single standard object OR an array of standard objects
     */
    public function dispatch($orders, $order) 
    {  
        // 1. Resolve service
        $service = $this->resolveService($order->app_id, $order->sid);

        // 2. Normalize input: If it's a single object, wrap it in an array
        $orderList = is_array($orders) ? $orders : [$orders];

        // 3. Send all orders to the service
        // The service now handles the formatting loop inside sendOrders
        $result = $service->sendOrders($orderList);

        // 4. If we sent multiple, return the raw result to the component for bulk processing
        if (is_array($orders)) {
            return $result;
        }

        // 5. If it was a single order, process the individual response as before
        $singleRef = $orderList[0]->ref;
        return $this->processResponse($singleRef, $result);
    }

    protected function resolveService($id, $sid)
    {  
        $installedApp = installedApps::where('sid', $sid)->where('app_id', $id)->first();
        return match ((int)$id) {
            1001 => new AndersonCreateOrderService($installedApp->token),
            1010 => new ZRCreateOrderService($installedApp->token),
            default => throw new \Exception("Carrier Service ID [{$id}] not found in Switcher."),
        };
    }

    protected function processResponse($ref, $result)
    {
        // EcoTrack structure check: result -> results -> {ref} -> success
        if (isset($result['results'][$ref]['success']) && $result['results'][$ref]['success']) {
            return [
                'success' => true,
                'tracking' => $result['results'][$ref]['tracking'],
            ];
        }

        return [
            'success' => false, 
            'message' => $result['results'][$ref]['errors'] ?? 'API error for ' . $ref
        ];
    }
}