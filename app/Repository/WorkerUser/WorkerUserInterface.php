<?php

namespace App\Repository\WorkerUser;

use App\DataTables\Dashboard\WorkerUserDatatable;
use App\Models\WorkerUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @mixin WorkerUserRepository
 */
interface WorkerUserInterface
{
    public function index(): LengthAwarePaginator;

    public function all();

    public function store(array $data): WorkerUser|bool;

    public function destroy(int|array|WorkerUser $model): bool;

    public function find(int|array|WorkerUser $model, callable $callable = null, bool $deleted = false): WorkerUser|Collection|null;

    public function delete(int|WorkerUser $model): WorkerUser|bool;

    public function forceDelete(int|WorkerUser $model): WorkerUser|bool;

    public function restore(int|WorkerUser $model): WorkerUser|bool;

    public function datatable(): WorkerUserDatatable|DataTable;

    public function toggleStatus(int|WorkerUser $model, bool $status = false): bool;

    public function update(int|WorkerUser $model, array $data): WorkerUser|bool;

    public function deletedOnly(): array|LengthAwarePaginator;
}
