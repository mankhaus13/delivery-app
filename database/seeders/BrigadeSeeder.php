<?php

namespace Database\Seeders;

use App\Models\Brigade;
use Illuminate\Database\Seeder;

final class BrigadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brigade::factory(1000)->create();
    }
}
