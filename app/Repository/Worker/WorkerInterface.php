<?php

namespace App\Repository\Worker;

use App\DataTables\Dashboard\WorkerDatatable;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @mixin WorkerRepository
 */
interface WorkerInterface
{
    public function index(): LengthAwarePaginator;

    public function all();

    public function store(array $data): Worker|bool;

    public function destroy(int|array|Worker $model): bool;

    public function find(int|array|Worker $model, callable $callable = null, bool $deleted = false): Worker|Collection|null;

    public function delete(int|Worker $model): Worker|bool;

    public function forceDelete(int|Worker $model): Worker|bool;

    public function restore(int|Worker $model): Worker|bool;

    public function datatable(): WorkerDatatable|DataTable;

    public function toggleStatus(int|Worker $model, bool $status = false): bool;

    public function update(int|Worker $model, array $data): Worker|bool;

    public function deletedOnly(): array|LengthAwarePaginator;

    public function orders(Worker $worker);

    public function finishOrder(Worker $worker, \App\Models\WorkerUser $order, array $before_images);
}
