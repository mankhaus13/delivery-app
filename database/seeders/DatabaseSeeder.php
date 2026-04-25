<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BodycheckSeeder::class,
            BrigadeSeeder::class,
            BrigadeMemberSeeder::class,
            CancelationReasonSeeder::class,
            BottlesDiscrepancyReasonSeeder::class,
            ShippingSeeder::class,
            OrderSeeder::class,
            ItemSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
