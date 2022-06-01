<?php

namespace App\Repository\Plan;

use App\DataTables\Dashboard\PlanDatatable;
use App\Models\Plan;
use App\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @property-read Plan $model
 */
class PlanRepository extends BaseRepository implements PlanInterface
{
    protected array $filters = [];

    /**
     * @param Plan $model
     */
    public function __construct(Plan $model)
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
     * @return Plan[]|Collection
     */
    public function all()
    {
        return $this->applyFilter($this->model->query())->get();
    }

    /**
     * @param array $data
     * @return Plan|bool
     */
    public function store(array $data): Plan|bool
    {
        // changes something

        if (!$saved = $this->model->create($data)) {
            return false;
        }
        // sync

        return $saved;
    }

    /**
     * @param int|int[]|Plan $model
     * @return bool
     */
    public function destroy(int|array|Plan $model): bool
    {
        if (!$model = $this->find($model)) return false;

        return $model instanceof Collection ? $model->toQuery()->delete() : $model->delete();
    }

    /**
     * @param int|Plan $model
     * @param callable|null $callable
     * @param bool $deleted
     * @return Plan|Collection|null
     */
    public function find(int|array|Plan $model, callable $callable = null, bool $deleted = false): Plan|Collection|null
    {
        if ($model instanceof Plan) return $model;

        $query = $this->model->query();

        if ($deleted) $query->withTrashed();

        if (is_callable($callable)) $callable($query);

        if (is_array($model)) return $query->findMany($model);

        return $query->find($model);
    }

    /**
     * @param int|Plan $model
     * @return Plan|bool
     */
    public function delete(int|Plan $model): Plan|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->delete();
    }

    /**
     * @param int|Plan $model
     * @return Plan|bool
     */
    public function forceDelete(int|Plan $model): Plan|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->forceDelete();
    }

    /**
     * @param int|Plan $model
     * @return Plan|bool
     */
    public function restore(int|Plan $model): Plan|bool
    {
        if (!$model = $this->find($model, deleted: true)) return false;

        return $model->restore();
    }

    /**
     * @return PlanDatatable|DataTable
     */
    public function datatable(): PlanDatatable|DataTable
    {
        return app(PlanDatatable::class);
    }

    /**
     * @param int|Plan $model
     * @param bool $status
     * @return bool
     */
    public function toggleStatus(int|Plan $model, bool $status = false): bool
    {
        $model = $this->find($model);

        return $model->update(compact('status'));
    }

    /**
     * @param int|Plan $model
     * @param array $data
     * @return Plan|bool
     */
    public function update(int|Plan $model, array $data): Plan|bool
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
     * @return LengthAwarePaginator
     */
    public function best(): LengthAwarePaginator
    {
        return $this->model->inRandomOrder()->paginate();
    }

}
