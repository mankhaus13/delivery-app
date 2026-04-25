<?php

namespace Database\Seeders;

use App\Models\Enums\Item\ItemType;
use App\Models\Item;
use Illuminate\Database\Seeder;

final class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::factory(10000)->create();
    }
}
