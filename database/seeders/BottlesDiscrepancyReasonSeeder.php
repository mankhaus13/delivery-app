<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BottlesDiscrepancyReason;
use Illuminate\Database\Seeder;

final class BottlesDiscrepancyReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BottlesDiscrepancyReason::factory(10)->create();
    }
}
