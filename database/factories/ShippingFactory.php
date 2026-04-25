<?php

namespace Database\Factories;

use App\Models\Shipping;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shipping>
 */
final class ShippingFactory extends Factory
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
            'expeditor_id' => User::inRandomOrder()->value('id'),
            'window_number' => $this->faker->randomNumber(1),
            'time_start' => $this->faker->time,
            'time_end' => $this->faker->time,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
