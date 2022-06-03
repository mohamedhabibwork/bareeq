<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\SingleRequest;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerUser;
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
        $plan = $this->faker->boolean() ? Plan::factory() : SingleRequest::factory();
        return [
            'user_id' => User::factory(),
            'worker_id' => $this->faker->boolean(80) ? Worker::inRandomOrder()->first()->id : Worker::factory()->create()->id,
            'plan_id' => $plan,
            'plan_type' => $plan->modelName(),
            'order_status' => rand(0, 3),
            'user_status' => rand(0, 3),
            'after_images' => [$this->faker->imageUrl()],
            'before_images' => [$this->faker->imageUrl()],
        ];
    }

    public function orderSuccess()
    {
        return $this->state(function (array $attributes) {
            return [
                'order_status' => WorkerUser::ORDER_STATUS['success'],
            ];
        });
    }

    public function orderPending()
    {
        return $this->state(function (array $attributes) {
            return [
                'order_status' => WorkerUser::ORDER_STATUS['pending'],
            ];
        });
    }

    public function orderProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'order_status' => WorkerUser::ORDER_STATUS['progress'],
            ];
        });
    }

    public function userChanged()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_status' => WorkerUser::USER_STATUS['changed'],
                'order_status' => WorkerUser::ORDER_STATUS['pending'],
            ];
        });
    }

    public function userSuccess()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_status' => WorkerUser::USER_STATUS['success'],
            ];
        });
    }

    public function userPending()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_status' => WorkerUser::USER_STATUS['pending'],
            ];
        });
    }
}
