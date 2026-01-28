<?php

namespace App\Services;

use App\Models\ProductAgent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderDistributorService
{
    public function getNextAgentId($sku, $defaultUserId = 1)
    {
        $today = Carbon::today()->toDateString();
        $sku = trim($sku);

        $this->checkAndResetNewDay($sku, $today);

        for ($attempt = 0; $attempt < 2; $attempt++) {
            $agentId = DB::transaction(function () use ($sku, $today) {
                
                $agent = ProductAgent::where('sku', $sku)
                    ->where('is_active', true)
                    ->whereColumn('daily_received', '<', 'portion')
                    ->orderBy('daily_received', 'asc') 
                    ->orderBy('portion', 'desc') 
                    ->lockForUpdate()
                    ->first();

                if ($agent) {
                    $agent->increment('daily_received');
                    $agent->update(['last_assigned_date' => $today]);
                    return $agent->aid;
                }

                return null;
            });

            if ($agentId) return $agentId;

   
            if (ProductAgent::where('sku', $sku)->where('is_active', true)->exists()) {
                ProductAgent::where('sku', $sku)->where('is_active', true)->update(['daily_received' => 0]);
                continue; 
            }
            break;
        }

        return $defaultUserId;
    }

    private function checkAndResetNewDay($sku, $today)
    {
        ProductAgent::where('sku', $sku)
            ->where(function ($q) use ($today) {
                $q->where('last_assigned_date', '<', $today)->orWhereNull('last_assigned_date');
            })->update(['daily_received' => 0, 'last_assigned_date' => $today]);
    }
}