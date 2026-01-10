<?php

namespace App\Services;

use App\Services\AndersonServices\AndersonEditOrderService;
use App\Services\ZRServices\ZREditOrderService;

class EditeOrderSwitcher
{
    /**
     * Now accepts a single standard object OR an array of standard objects
     */
    public function dispatch($tracking,$orders, $companyId) 
    {   
        
        // 1. Resolve service
        $service = $this->resolveService($companyId);
        
        $result = $service->updateOrder($tracking,$orders);
          
        return $result;
    }

    protected function resolveService($id)
    {
        return match ((int)$id) {
            1001 => new AndersonEditOrderService(),
            1010 => new ZREditOrderService(),
            default => throw new \Exception("Carrier Service ID [{$id}] not found in Switcher."),
        };
    }
}