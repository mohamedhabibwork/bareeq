<?php

namespace App\Repository\City;
use Illuminate\Database\Eloquent\Collection;
use Yajra\DataTables\Services\DataTable;

/**
 * @mixin CityRepository
 */
interface CityInterface
{
    public function index():\Illuminate\Pagination\LengthAwarePaginator;
    public function all();
    public function store(array $data): \App\Models\City|bool;
    public function destroy(int|array|\App\Models\City $model): bool;
    public function find(int|array|\App\Models\City $model, callable $callable = null, bool $deleted = false): \App\Models\City|Collection|null;
    public function delete(int|\App\Models\City $model): \App\Models\City|bool;
    public function forceDelete(int|\App\Models\City $model): \App\Models\City|bool;
    public function restore(int|\App\Models\City $model): \App\Models\City|bool;
    public function datatable(): \App\DataTables\Dashboard\CityDatatable|DataTable;
    public function toggleStatus(int|\App\Models\City $model, bool $status = false): bool;
    public function update(int|\App\Models\City $model, array $data): \App\Models\City|bool;
    public function deletedOnly(): array|\Illuminate\Pagination\LengthAwarePaginator;
}
