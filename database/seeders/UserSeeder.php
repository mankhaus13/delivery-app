<?php

namespace Database\Seeders;

use App\Models\Enums\User\UserPosition;
use App\Models\Enums\User\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Олег',
            'second_name' => 'Костантинопольский',
            'surname' => 'Коряков',
            'phone' => '81234567898',
            'password' => '$2y$12$lAV82K/UYpAKJ5qmK/QlIOU7RIRZhAZvrjr.eqzVRwK512y7kkzQe', //demodemo
            'role' => UserRole::Developer->value,
            'external_id' => '4888fa38-be1d-3823-8113-e818fe5b52e9',
        ]);
        User::factory(10)->create();
    }
}
