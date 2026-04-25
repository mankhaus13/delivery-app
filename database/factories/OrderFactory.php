<?php

namespace Database\Factories;

use App\Models\Enums\Order\OrderStatus;
use App\Models\Enums\Order\PaymentMethod;
use App\Models\Enums\Order\Period;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
final class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'external_id' => $this->faker->uuid,
            'number' => $this->faker->bothify(str_repeat('?#', 5)),
            'expeditor_id' => User::inRandomOrder()->value('id'),
            'return_bottles' => $this->faker->numberBetween(1, 10),
            'empty_bottles' => $this->faker->numberBetween(1, 10),
            'payment_method' => $this->faker->randomElement(PaymentMethod::values()),
            'status' => $this->faker->randomElement(OrderStatus::values()),
            'period' => $this->faker->randomElement(Period::values()),
            'client_name' => $this->faker->name,
            'address' => $this->faker->address,
            'address_extra_info' => $this->faker->sentence, //подъезд лифт этаж
            'shipping_date' => $this->faker->dateTimeThisMonth, //$this->faker->date,
            'total' => (float) $this->faker->numberBetween(100, 10000),
            'expected_delivery_time' => $this->faker->time,
            'order_comment' => $this->faker->sentence(),
            'address_comment' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
