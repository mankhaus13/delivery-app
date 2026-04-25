<?php

namespace Database\Factories;

use App\Models\Enums\Item\ItemType;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
final class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(
                [
                    'Вода питьевая "Ключевая вода" 19 л',
                    'Вода питьевая ЙОДированная "Ключевая вода" 19 л',
                    'Вода питьевая "Ключевая линия" 5 л',
                ]
            ),
            'image' => 'image.jpg',
            'quantity' => $this->faker->numberBetween(1, 10),
            'price' => (float) $this->faker->numberBetween(100, 10000),
            'order_id' => Order::inRandomOrder()->value('id'),
            'type' => $this->faker->randomElement(ItemType::values()),
        ];
    }
}
