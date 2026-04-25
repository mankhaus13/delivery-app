<?php

namespace Database\Seeders;

use App\Models\Bodycheck;
use Illuminate\Database\Seeder;

final class BodycheckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bodycheck::factory(1000)->create();
    }
}
