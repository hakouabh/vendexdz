<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        foreach ($orders as $order) {
            $user = User::find($order->sid);
            if($user && $user->userStore) {
                $order->sid = $user->userStore->store_id;
                $order->save();
            } else {
                $order->delete();
            }
        }
    }
}
