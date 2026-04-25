<?php

namespace Database\Factories;

use App\Models\Brigade;
use App\Models\Enums\Order\Period;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<Brigade>
 */
final class BrigadeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'expeditor_id' => User::inRandomOrder()->value('id'),
            'date' => $this->faker->dateTimeThisMonth,
            'period' => $this->faker->randomElement(Period::values()),
            'car_id' => 'Е013УК',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
