<?php

namespace App\Repository\SingleRequest;

use App\DataTables\Dashboard\SingleRequestDatatable;
use App\Models\SingleRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @mixin SingleRequestRepository
 */
interface SingleRequestInterface
{
    public function index(): LengthAwarePaginator;

    public function all();

    public function store(array $data): SingleRequest|bool;

    public function destroy(int|array|SingleRequest $model): bool;

    public function find(int|array|SingleRequest $model, callable $callable = null, bool $deleted = false): SingleRequest|Collection|null;

    public function delete(int|SingleRequest $model): SingleRequest|bool;

    public function forceDelete(int|SingleRequest $model): SingleRequest|bool;

    public function restore(int|SingleRequest $model): SingleRequest|bool;

    public function datatable(): SingleRequestDatatable|DataTable;

    public function toggleStatus(int|SingleRequest $model, bool $status = false): bool;

    public function update(int|SingleRequest $model, array $data): SingleRequest|bool;

    public function deletedOnly(): array|LengthAwarePaginator;
}
