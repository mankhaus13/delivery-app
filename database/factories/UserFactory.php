<?php

namespace Database\Factories;

use App\Models\Enums\User\UserPosition;
use App\Models\Enums\User\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'second_name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
            'password' => self::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'external_id' => $this->faker->uuid,
            'role' => $this->faker->randomElement(UserRole::values()),
        ];
    }
}
