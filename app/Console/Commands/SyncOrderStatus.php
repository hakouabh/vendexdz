<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
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

                                    $order->Waiting()->delete();

                                    $order->Indelivery()->updateOrCreate(
                                        ['oid' => $order->oid],
                                        [
                                            'ssid' => $internalStatus,
                                        ]
                                    );
                                } else {
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
