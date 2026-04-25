<?php

namespace Database\Factories;

use App\Models\Brigade;
use App\Models\BrigadeMember;
use App\Models\Enums\User\UserPosition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BrigadeMember>
 */
final class BrigadeMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'position' => $this->faker->randomElement(UserPosition::values()),
            'brigade_id' => Brigade::inRandomOrder()->value('id'),
            'fio' => $this->faker->firstName() . ' ' . $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'telephone' => $this->faker->phoneNumber(),
        ];
    }
}
