<?php

namespace App\Repository\UserPlan;

use App\DataTables\Dashboard\UserPlanDatatable;
use App\Models\UserPlan;
use App\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @property-read UserPlan $model
 */
class UserPlanRepository extends BaseRepository implements UserPlanInterface
{
    protected array $filters = [];

    /**
     * @param UserPlan $model
     */
    public function __construct(UserPlan $model)
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
     * @return UserPlan[]|Collection
     */
    public function all()
    {
        return $this->applyFilter($this->model->query())->get();
    }

    /**
     * @param array $data
     * @return UserPlan|bool
     */
    public function store(array $data): UserPlan|bool
    {
        // changes something

        if (!$saved = $this->model->create($data)) {
            return false;
        }
        // sync

        return $saved;
    }

    /**
     * @param int|int[]|UserPlan $model
     * @return bool
     */
    public function destroy(int|array|UserPlan $model): bool
    {
        if (!$model = $this->find($model)) return false;

        return $model instanceof Collection ? $model->toQuery()->delete() : $model->delete();
    }

    /**
     * @param int|UserPlan $model
     * @param callable|null $callable
     * @param bool $deleted
     * @return UserPlan|Collection|null
     */
    public function find(int|array|UserPlan $model, callable $callable = null, bool $deleted = false): UserPlan|Collection|null
    {
        if ($model instanceof UserPlan) return $model;

        $query = $this->model->query();

        if ($deleted) $query->withTrashed();

        if (is_callable($callable)) $callable($query);

        if (is_array($model)) return $query->findMany($model);

        return $query->find($model);
    }

    /**
     * @param int|UserPlan $model
     * @return UserPlan|bool
     */
    public function delete(int|UserPlan $model): UserPlan|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->delete();
    }

    /**
     * @param int|UserPlan $model
     * @return UserPlan|bool
     */
    public function forceDelete(int|UserPlan $model): UserPlan|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->forceDelete();
    }

    /**
     * @param int|UserPlan $model
     * @return UserPlan|bool
     */
    public function restore(int|UserPlan $model): UserPlan|bool
    {
        if (!$model = $this->find($model, deleted: true)) return false;

        return $model->restore();
    }

    /**
     * @return UserPlanDatatable|DataTable
     */
    public function datatable(): UserPlanDatatable|DataTable
    {
        return app(UserPlanDatatable::class);
    }

    /**
     * @param int|UserPlan $model
     * @param bool $status
     * @return bool
     */
    public function toggleStatus(int|UserPlan $model, bool $status = false): bool
    {
        $model = $this->find($model);

        return $model->update(compact('status'));
    }

    /**
     * @param int|UserPlan $model
     * @param array $data
     * @return UserPlan|bool
     */
    public function update(int|UserPlan $model, array $data): UserPlan|bool
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
