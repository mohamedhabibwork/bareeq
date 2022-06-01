<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->name,
            'price'=>$this->faker->numberBetween(1,1000),
            'description'=>$this->faker->sentence(rand(3,6),true),
            'wishing_count'=>$this->faker->numberBetween(1,20),
            'status'=>$this->faker->boolean(),
            'images'=>[$this->faker->imageUrl()],
        ];
    }
}
