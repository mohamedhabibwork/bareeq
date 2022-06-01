<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class WorkerUserFactory extends Factory
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
            'worker_id' => $this->faker->boolean(80) ? Worker::inRandomOrder()->first()->id : Worker::factory(),
            'plan_id' => Plan::factory(),
            'order_status' => rand(0, 3),
            'user_status' => rand(0, 3),
            'after_images' => [$this->faker->imageUrl()],
            'before_images' => [$this->faker->imageUrl()],
        ];
    }
}
