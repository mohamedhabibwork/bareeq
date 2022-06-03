<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPlan>
 */
class UserPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->boolean(80) ? (User::inRandomOrder()->first()?->id?:User::factory()):User::factory(),
            'plan_id' => $this->faker->boolean(80) ? (Plan::inRandomOrder()->first()?->id?:Plan::factory()):Plan::factory(),
        ];
    }
}
