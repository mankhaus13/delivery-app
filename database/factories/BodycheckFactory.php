<?php

namespace Database\Factories;

use App\Models\Bodycheck;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bodycheck>
 */
final class BodycheckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->dateTimeThisMonth,
            'time_start' => $this->faker->time(),
            'expeditor_id' => User::inRandomOrder()->value('id'),
            'passed' => $this->faker->boolean,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
