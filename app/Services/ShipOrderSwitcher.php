<?php

namespace App\Services;
use App\Services\AndersonServices\AndersonShipOrderService;
use App\Services\ZRServices\ZRShipOrderService;
class ShipOrderSwitcher
{
    /**
     * Finalize and ship an order (Request Collection)
     */
    public function validate($tracking, $companyId)
    { 
        $service = $this->resolveShipService($companyId);
        return $service->validateAndShip($tracking);
    }

    protected function resolveShipService($id)
    {
        return match ((int)$id) {
            1001 => new AndersonShipOrderService(),
            1010 => new ZRShipOrderService(),
            default => throw new \Exception("Carrier [{$id}] not found."),
        };
    }
}