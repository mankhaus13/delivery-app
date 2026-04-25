<?php

namespace Database\Seeders;

use App\Models\BrigadeMember;
use Illuminate\Database\Seeder;

final class BrigadeMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BrigadeMember::factory(1000)->create();
    }
}
