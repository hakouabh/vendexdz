<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fees = \App\Models\fees::all();
        foreach ($fees as $fee) {
            $product = \App\Models\Product::where('sku', $fee->product_id)->first();
            if ($product) {
                $fee->product_id = $product->id;
                $fee->save();
            }else{
                $fee->delete();
            }
        }
    }
}
