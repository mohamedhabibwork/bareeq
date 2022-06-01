<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'image' => $this->faker->imageUrl(),
            'type' => $this->faker->bloodType(),
            'color' => $this->faker->colorName(),
            'plate_number' => $this->faker->buildingNumber()
        ];
    }
}
