<?php

namespace Database\Factories;

use App\Models\CancelationReason;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CancelationReason>
 */
final class CancelationReasonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reason' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
