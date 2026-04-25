<?php

namespace Database\Factories;

use App\Models\Enums\Notification\NotificationOrderStatus;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
final class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => $this->faker->sentence,
            'viewed' => $this->faker->boolean,
            'expeditor_id' => User::inRandomOrder()->value('id'),
            'order_id' => Order::inRandomOrder()->value('id'),
            'status' => $this->faker->randomElement(NotificationOrderStatus::values()),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
