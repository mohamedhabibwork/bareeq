<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Car;
use App\Models\Plan;
use App\Models\SingleRequest;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\Worker;
use App\Models\WorkerUser;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Admin::factory()->create([
            'name' => 'admin',
            'email' => 'admin@app.com',
            'password' => '123456789'
        ]);

        Plan::factory(5)->create();

        User::factory()
            ->has(Car::factory(),'car')
            ->has(SingleRequest::factory(5),'single_requests')->create([
            'name' => 'admin',
            'phone' => '01098989297',
            'password' => '123456789'
        ]);

        UserPlan::factory(5)->create();
        Worker::factory()->create([
            'name' => 'admin',
            'phone' => '01098989297',
            'password' => '123456789'
        ]);
        WorkerUser::factory(50)->create();

    }
}
