<?php

namespace App\Repository\WorkerUser;

use App\DataTables\Dashboard\WorkerUserDatatable;
use App\Models\WorkerUser;
use App\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @property-read WorkerUser $model
 */
class WorkerUserRepository extends BaseRepository implements WorkerUserInterface
{
    protected array $filters = [];

    /**
     * @param WorkerUser $model
     */
    public function __construct(WorkerUser $model)
    {
        parent::__construct($model);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return $this->applyFilter($this->model->query())->paginate();
    }

    /**
     * @return WorkerUser[]|Collection
     */
    public function all()
    {
        return $this->applyFilter($this->model->query())->get();
    }

    /**
     * @param array $data
     * @return WorkerUser|bool
     */
    public function store(array $data): WorkerUser|bool
    {
        // changes something

        if (!$saved = $this->model->create($data)) {
            return false;
        }
        // sync

        return $saved;
    }

    /**
     * @param int|int[]|WorkerUser $model
     * @return bool
     */
    public function destroy(int|array|WorkerUser $model): bool
    {
        if (!$model = $this->find($model)) return false;

        return $model instanceof Collection ? $model->toQuery()->delete() : $model->delete();
    }

    /**
     * @param int|WorkerUser $model
     * @param callable|null $callable
     * @param bool $deleted
     * @return WorkerUser|Collection|null
     */
    public function find(int|array|WorkerUser $model, callable $callable = null, bool $deleted = false): WorkerUser|Collection|null
    {
        if ($model instanceof WorkerUser) return $model;

        $query = $this->model->query();

        if ($deleted) $query->withTrashed();

        if (is_callable($callable)) $callable($query);

        if (is_array($model)) return $query->findMany($model);

        return $query->find($model);
    }

    /**
     * @param int|WorkerUser $model
     * @return WorkerUser|bool
     */
    public function delete(int|WorkerUser $model): WorkerUser|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->delete();
    }

    /**
     * @param int|WorkerUser $model
     * @return WorkerUser|bool
     */
    public function forceDelete(int|WorkerUser $model): WorkerUser|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->forceDelete();
    }

    /**
     * @param int|WorkerUser $model
     * @return WorkerUser|bool
     */
    public function restore(int|WorkerUser $model): WorkerUser|bool
    {
        if (!$model = $this->find($model, deleted: true)) return false;

        return $model->restore();
    }

    /**
     * @return WorkerUserDatatable|DataTable
     */
    public function datatable(): WorkerUserDatatable|DataTable
    {
        return app(WorkerUserDatatable::class);
    }

    /**
     * @param int|WorkerUser $model
     * @param bool $status
     * @return bool
     */
    public function toggleStatus(int|WorkerUser $model, bool $status = false): bool
    {
        $model = $this->find($model);

        return $model->update(compact('status'));
    }

    /**
     * @param int|WorkerUser $model
     * @param array $data
     * @return WorkerUser|bool
     */
    public function update(int|WorkerUser $model, array $data): WorkerUser|bool
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

    /**
     * @param string|null $date
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDayOrders(?string $date = null)
    {
        return $this->applyFilter($this->model->whereNull('worker_id')->whereDate('created_at', $date ?? today())->with('user'))->paginate();
    }
}
