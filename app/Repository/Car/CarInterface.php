<?php

namespace App\Repository\Car;

use App\Models\Car;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @mixin CarRepository
 */
interface CarInterface
{
    public function index(): LengthAwarePaginator;

    public function all();

    public function store(array $data): Car|bool;

    public function destroy(int|array|Car $model): bool;

    public function find(int|array|Car $model, callable $callable = null, bool $deleted = false): Car|Collection|null;

    public function delete(int|Car $model): Car|bool;

    public function forceDelete(int|Car $model): Car|bool;

    public function restore(int|Car $model): Car|bool;

    public function datatable(): CarDatatable|DataTable;

    public function toggleStatus(int|Car $model, bool $status = false): bool;

    public function update(int|Car $model, array $data): Car|bool;

    public function deletedOnly(): array|LengthAwarePaginator;
}
