<?php

namespace Database\Factories;

use App\Models\SingleRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class SingleRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'car_name' => $this->faker->name(),
            'car_type' => $this->faker->bloodType(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'car_area' => $this->faker->streetAddress,
            'user_id' => User::factory(),
        ];
    }
}
