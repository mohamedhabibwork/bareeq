<?php

namespace App\Repository\UserPlan;

use App\DataTables\Dashboard\UserPlanDatatable;
use App\Models\UserPlan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @mixin UserPlanRepository
 */
interface UserPlanInterface
{
    public function index(): LengthAwarePaginator;

    public function all();

    public function store(array $data): UserPlan|bool;

    public function destroy(int|array|UserPlan $model): bool;

    public function find(int|array|UserPlan $model, callable $callable = null, bool $deleted = false): UserPlan|Collection|null;

    public function delete(int|UserPlan $model): UserPlan|bool;

    public function forceDelete(int|UserPlan $model): UserPlan|bool;

    public function restore(int|UserPlan $model): UserPlan|bool;

    public function datatable(): UserPlanDatatable|DataTable;

    public function toggleStatus(int|UserPlan $model, bool $status = false): bool;

    public function update(int|UserPlan $model, array $data): UserPlan|bool;

    public function deletedOnly(): array|LengthAwarePaginator;
}
