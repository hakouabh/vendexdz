<?php

namespace App\Services;
use App\Services\AndersonServices\AndersonDeleteOrderService;
use App\Services\ZRServices\ZRDeleteOrderService;
class RemoveOrderSwitcher
{
    /**
     * Finalize and ship an order (Request Collection)
     */
    public function validate($tracking, $companyId)
    {   
        $service = $this->resolveDeleteService($companyId);
        return $service->RemoveOrder($tracking);
    }

    protected function resolveDeleteService($id)
    {
        return match ((int)$id) {
            1001 => new AndersonDeleteOrderService(),
            1010 => new ZRDeleteOrderService(),
            default => throw new \Exception("Carrier [{$id}] not found."),
        };
    }
}