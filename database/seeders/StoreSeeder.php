<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use App\Models\UserStore;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('id', '!=', 23)->get();
        foreach ($users as $user) {
            $store = Store::create([
                'name' => $user->name . "'s Store",
                'created_by' => $user->id,
            ]);
            UserStore::create([
                'user_id' => $user->id,
                'store_id' => $store->id
            ]);
        }
    }
}
