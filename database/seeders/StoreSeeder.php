<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::whereNotNull('store_id')->get();
        foreach ($users as $user) {
            \App\Models\UserStore::create([
                'store_id' => $user->store_id,
                'user_id' => $user->id,
            ]);
        }
    }
}
