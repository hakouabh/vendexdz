<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstalledAppIsDefaultSeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::statement("
            UPDATE installed_apps t
            JOIN (
                SELECT id,
                    ROW_NUMBER() OVER (PARTITION BY sid ORDER BY id) as rn
                FROM installed_apps
            ) ranked ON t.id = ranked.id
            SET t.is_default = (ranked.rn = 1)
        ");
    }
}
