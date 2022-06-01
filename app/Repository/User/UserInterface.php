<?php

namespace App\Repository\User;

use App\Models\Plan;
use App\Models\User;
use App\Models\WorkerUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * @mixin UserRepository
 */
interface UserInterface
{
    public function getDayUsers(?string $day = null);

    public function store(array $data): User|bool;

    public function update(int|User $model, array $data): User|bool;

    public function delete(int|User $model): User|bool;

    public function find(int|User $model, callable $callable = null, bool $deleted = false): User|Collection|null;

    public function destroy(int|array|User $model): bool;

    public function forceDelete(int|User $model): User|bool;

    public function restore(int|User $model): User|bool;

    public function orders(User $user);

    public function notifications(User $user);

    public function createOrder(User $user, Plan $plan);

    public function subscribe(User $user, int|Plan $plan_id);

    public function singles(User $user);

    public function createSingleRequest(User $user, array $validatedData);

    public function attachCar(User $user,array $data);

}
