<?php

namespace App\Repository\Admin;

use App\DataTables\Dashboard\AdminDatatable;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\Services\DataTable;

/**
 * @mixin AdminRepository
 */
interface AdminInterface
{
    public function index();

    public function all();

    public function store(array $data): Admin|bool;

    public function destroy(int|array|Admin $model): bool;

    public function find(int|array|Admin $model, callable $callable = null, bool $deleted = false): Admin|Collection|null;

    public function delete(int|Admin $model): Admin|bool;

    public function forceDelete(int|Admin $model): Admin|bool;

    public function restore(int|Admin $model): Admin|bool;

    public function datatable(): AdminDatatable|DataTable;

    public function exportExcel(): Response|BinaryFileResponse;

    public function toggleStatus(int|Admin $model, bool $status = false): bool;

    public function update(int|Admin $model, array $data): Admin|bool;

    public function deletedOnly(): array|LengthAwarePaginator;
}
