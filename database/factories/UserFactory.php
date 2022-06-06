<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'phone_verified_at' => now(),
            'password' => '123456789', // password
            'remember_token' => Str::random(10),
            'start_time' => $this->faker->time('H:i'),
            'end_time' => $this->faker->time('H:i'),
            'wish_day' => $this->faker->dayOfWeek(),
            'status' => $this->faker->boolean(),
            'city_id' => City::factory(),
        ];
    }

    /**
     * Indicate that the model's phone number should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'phone_verified_at' => null,
            ];
        });
    }
}
