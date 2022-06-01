<?php

namespace App\Repository\Admin;

use App\DataTables\Dashboard\AdminDatatable;
use App\Models\Admin;
use App\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\Services\DataTable;

/**
 * @property-read Admin $model
 */
class AdminRepository extends BaseRepository implements AdminInterface
{
    protected array $filters = [];

    /**
     * @param Admin $model
     */
    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function index()
    {
        return $this->applyFilter($this->model->query())->paginate();
    }

    /**
     * @return Admin[]|Collection
     */
    public function all()
    {
        return $this->applyFilter($this->model->query())->get();
    }

    /**
     * @param array $data
     * @return Admin|bool
     */
    public function store(array $data): Admin|bool
    {
        // changes something

        if (!$saved = $this->model->create($data)) {
            return false;
        }
        // sync

        return $saved;
    }

    /**
     * @param int|int[]|Admin $model
     * @return bool
     */
    public function destroy(int|array|Admin $model): bool
    {
        if (!$model = $this->find($model)) return false;

        return $model instanceof Collection ? $model->toQuery()->delete() : $model->delete();
    }

    /**
     * @param int|Admin $model
     * @param callable|null $callable
     * @param bool $deleted
     * @return Admin|Collection|null
     */
    public function find(int|array|Admin $model, callable $callable = null, bool $deleted = false): Admin|Collection|null
    {
        if ($model instanceof Admin) return $model;

        $query = $this->model->query();

        if ($deleted) $query->withTrashed();

        if (is_callable($callable)) $callable($query);

        if (is_array($model)) return $query->findMany($model);

        return $query->find($model);
    }

    /**
     * @param int|Admin $model
     * @return Admin|bool
     */
    public function delete(int|Admin $model): Admin|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->delete();
    }

    /**
     * @param int|Admin $model
     * @return Admin|bool
     */
    public function forceDelete(int|Admin $model): Admin|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->forceDelete();
    }

    /**
     * @param int|Admin $model
     * @return Admin|bool
     */
    public function restore(int|Admin $model): Admin|bool
    {
        if (!$model = $this->find($model, deleted: true)) return false;

        return $model->restore();
    }

    /**
     * @return AdminDatatable|DataTable
     */
    public function datatable(): AdminDatatable|DataTable
    {
        return app(AdminDatatable::class);
    }

    /**
     * @return Response|BinaryFileResponse
     */
    public function exportExcel(): Response|BinaryFileResponse
    {
        return (new AdminExport)->download('users.xlsx', Excel::CSV, ['Content-Type' => 'text/csv']);
    }

    /**
     * @param int|Admin $model
     * @param bool $status
     * @return bool
     */
    public function toggleStatus(int|Admin $model, bool $status = false): bool
    {
        $model = $this->find($model);

        return $model->update(compact('status'));
    }

    /**
     * @param int|Admin $model
     * @param array $data
     * @return Admin|bool
     */
    public function update(int|Admin $model, array $data): Admin|bool
    {
        if (!$model = $this->find($model)) return false;
        // changes something

        if (!$model->update($data)) return false;
        // sync

        return $model;
    }

    /**
     * @return array|LengthAwarePaginator
     */
    public function deletedOnly(): array|LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->paginate();
    }
}
