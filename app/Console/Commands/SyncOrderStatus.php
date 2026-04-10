<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\orderLog;
use App\Services\TrackingOrderSwitcher;

class SyncOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:sync-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync order statuses from remote 3pl to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Order::query()
            ->where(function ($q) {
                $q->whereHas('Waiting')
                    ->orWhereHas('Indelivery');
            })
            ->select('id', 'tracking', 'sid', 'app_id', 'oid')
            ->chunkById(1000, function ($orders) {
                $groups = $orders->groupBy(fn($o) => $o->sid . '-' . $o->app_id);

                foreach ($groups as $group) {
                    $switcher = new TrackingOrderSwitcher();
                    $group->pluck('tracking')
                    ->chunk(50)
                    ->each(function ($chunk) use ($group, $switcher) {
                        
                        $subset = $group->whereIn('tracking', $chunk);
                        
                        $results = $switcher->dispatch($subset);
                        
                        foreach ($results as $result) {
                            
                            $order = $subset->firstWhere('tracking', $result['tracking']);
                            
                            if (!$order) {
                                continue;
                                }
                                
                                $internalStatus = $result['internal_status'];
                                $remoteStatus = $result['remote_status'];
                                
                                if ($remoteStatus !== 'prete_a_expedier') {
                                    
                                    if($order->Waiting()->exists()) {
                                        OrderLog::create([
                                            'oid'       => $order->oid,
                                            'aid'       => 9,
                                            'step'      => 2,
                                            'statu_old' => $order->Waiting->ssid,
                                            'statu_new' => $internalStatus,
                                            'text'      => trans('Status updated from Waiting to Indelivery'),
                                            ]);
                                            \Log::alert("Status updated from Waiting to Indelivery");
                                        $order->Waiting()->delete();
                                    }else if($order->Indelivery()->exists()) {
                                        OrderLog::create([
                                            'oid'       => $order->oid,
                                            'aid'       => 9,
                                            'step'      => 2,
                                            'statu_old' => $order->Indelivery->ssid,
                                            'statu_new' => $internalStatus,
                                            'text' => trans('Status updated with new status'),
                                        ]);
                                        \Log::alert("Status updated with new status");
                                    }

                                    $order->Indelivery()->updateOrCreate(
                                        ['oid' => $order->oid],
                                        [
                                            'ssid' => $internalStatus,
                                        ]
                                    );
                                } else {
                                    if($order->Inconfirmation()->exists()) {
                                        OrderLog::create([
                                            'oid'       => $order->oid,
                                            'aid'       => 9,
                                            'statu_old' => $order->Inconfirmation->fsid,
                                            'statu_new' => $internalStatus,
                                            'text' => trans('Status updated to Waiting'),
                                        ]);
                                        \Log::alert("Status updated to Waiting");
                                    }
                                    $order->Waiting()->updateOrCreate(
                                        ['oid' => $order->oid],
                                        [
                                            'ssid' => $internalStatus,
                                        ]
                                    );
                                }
                            }
                        });
                }
            });
    }
}
