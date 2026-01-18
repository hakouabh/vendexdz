<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order_items = \App\Models\OrderItems::all();
        foreach ($order_items as $item) {
            $product = \App\Models\Product::where('sku', $item->sku)->first();
            if ($product) {
                $item->product_id = $product->id;
                $item->save();
            }else{
                $item->delete();
            }
        }
    }
}
