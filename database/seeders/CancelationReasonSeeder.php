<?php

namespace Database\Seeders;

use App\Models\CancelationReason;
use Illuminate\Database\Seeder;

final class CancelationReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CancelationReason::factory(10)->create();
    }
}
