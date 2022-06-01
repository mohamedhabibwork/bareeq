<?php

namespace App\Repository\Plan;

use App\DataTables\Dashboard\PlanDatatable;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @mixin PlanRepository
 */
interface PlanInterface
{
    public function index(): LengthAwarePaginator;

    public function all();

    public function store(array $data): Plan|bool;

    public function destroy(int|array|Plan $model): bool;

    public function find(int|array|Plan $model, callable $callable = null, bool $deleted = false): Plan|Collection|null;

    public function delete(int|Plan $model): Plan|bool;

    public function forceDelete(int|Plan $model): Plan|bool;

    public function restore(int|Plan $model): Plan|bool;

    public function datatable(): PlanDatatable|DataTable;

    public function toggleStatus(int|Plan $model, bool $status = false): bool;

    public function update(int|Plan $model, array $data): Plan|bool;

    public function deletedOnly(): array|LengthAwarePaginator;

    public function best(): LengthAwarePaginator;

}
