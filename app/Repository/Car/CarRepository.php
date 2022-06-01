<?php

namespace App\Repository\Car;

use App\DataTables\Dashboard\CarDatatable;
use App\Models\Car;
use App\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @property-read Car $model
 */
class CarRepository extends BaseRepository implements CarInterface
{
    protected array $filters = [];

    /**
     * @param Car $model
     */
    public function __construct(Car $model)
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
     * @return Car[]|Collection
     */
    public function all()
    {
        return $this->applyFilter($this->model->query())->get();
    }

    /**
     * @param array $data
     * @return Car|bool
     */
    public function store(array $data): Car|bool
    {
        // changes something

        if (!$saved = $this->model->create($data)) {
            return false;
        }
        // sync

        return $saved;
    }

    /**
     * @param int|int[]|Car $model
     * @return bool
     */
    public function destroy(int|array|Car $model): bool
    {
        if (!$model = $this->find($model)) return false;

        return $model instanceof Collection ? $model->toQuery()->delete() : $model->delete();
    }

    /**
     * @param int|Car $model
     * @param callable|null $callable
     * @param bool $deleted
     * @return Car|Collection|null
     */
    public function find(int|array|Car $model, callable $callable = null, bool $deleted = false): Car|Collection|null
    {
        if ($model instanceof Car) return $model;

        $query = $this->model->query();

        if ($deleted) $query->withTrashed();

        if (is_callable($callable)) $callable($query);

        if (is_array($model)) return $query->findMany($model);

        return $query->find($model);
    }

    /**
     * @param int|Car $model
     * @return Car|bool
     */
    public function delete(int|Car $model): Car|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->delete();
    }

    /**
     * @param int|Car $model
     * @return Car|bool
     */
    public function forceDelete(int|Car $model): Car|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->forceDelete();
    }

    /**
     * @param int|Car $model
     * @return Car|bool
     */
    public function restore(int|Car $model): Car|bool
    {
        if (!$model = $this->find($model, deleted: true)) return false;

        return $model->restore();
    }

    /**
     * @return CarDatatable|DataTable
     */
    public function datatable(): CarDatatable|DataTable
    {
        return app(CarDatatable::class);
    }

    /**
     * @param int|Car $model
     * @param bool $status
     * @return bool
     */
    public function toggleStatus(int|Car $model, bool $status = false): bool
    {
        $model = $this->find($model);

        return $model->update(compact('status'));
    }

    /**
     * @param int|Car $model
     * @param array $data
     * @return Car|bool
     */
    public function update(int|Car $model, array $data): Car|bool
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
