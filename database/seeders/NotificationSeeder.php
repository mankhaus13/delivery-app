<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

final class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification::factory(1000)->create();
    }
}
