<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderLog;
use App\Models\Order;

class OrderLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indeliveryOrders = Order::where(function ($q) {
                $q->whereHas('Waiting')
                    ->orWhereHas('Indelivery');
            })->with(['Waiting', 'Indelivery'])->get();
        foreach ($indeliveryOrders as $order) {
            OrderLog::updateOrCreate([
                'oid' => $order->oid,
                'aid' => 9,
                'step' => 2,
            ], [
                'statu_old' => 1,
                'statu_new' => $order->Waiting->ssid ?? $order->Indelivery->ssid ?? 1,
                'text' => 'Order updated to In Delivery status'
            ]);
        }
    }
}
