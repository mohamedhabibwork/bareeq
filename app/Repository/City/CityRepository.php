<?php

namespace App\Repository\City;
use App\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @property-read \App\Models\City $model
 */
class CityRepository extends BaseRepository implements CityInterface
{
     protected array $filters = [];

    /**
     * @param \App\Models\City $model
     */
    public function __construct(\App\Models\City $model)
    {
        parent::__construct($model);
    }
    /**
     * @return LengthAwarePaginator
     */
    public function index():LengthAwarePaginator
    {
        return $this->applyFilter($this->model->query())->paginate();
    }

    /**
     * @return \App\Models\City[]|Collection
     */
    public function all()
    {
        return $this->applyFilter($this->model->query())->get();
    }

    /**
     * @param array $data
     * @return \App\Models\City|bool
     */
    public function store(array $data): \App\Models\City|bool
    {
        if ($locations  = $data['locations'] ?? null) {
            unset($data['locations']);
            $data['cities.locations'] = "ST_GeomFromText('{$locations}',0)";
        }

        if (!$saved = $this->model->create($data)) {
            return false;
        }

        return $saved;
    }

    /**
     * @param int|int[]|\App\Models\City $model
     * @return bool
     */
    public function destroy(int|array|\App\Models\City $model): bool
    {
        if (!$model = $this->find($model)) return false;

        return $model instanceof Collection ? $model->toQuery()->delete() : $model->delete();
    }

    /**
     * @param int|\App\Models\City $model
     * @param callable|null $callable
     * @param bool $deleted
     * @return \App\Models\City|Collection|null
     */
    public function find(int|array|\App\Models\City $model, callable $callable = null, bool $deleted = false): \App\Models\City|Collection|null
    {
        if ($model instanceof \App\Models\City) return $model;

        $query = $this->model->query();

        if ($deleted) $query->withTrashed();

        if (is_callable($callable)) $callable($query);

        if (is_array($model)) return  $query->findMany($model);

        return $query->find($model);
    }

    /**
     * @param int|\App\Models\City $model
     * @return \App\Models\City|bool
     */
    public function delete(int|\App\Models\City $model): \App\Models\City|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->delete();
    }

    /**
     * @param int|\App\Models\City $model
     * @return \App\Models\City|bool
     */
    public function forceDelete(int|\App\Models\City $model): \App\Models\City|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->forceDelete();
    }

    /**
     * @param int|\App\Models\City $model
     * @return \App\Models\City|bool
     */
    public function restore(int|\App\Models\City $model): \App\Models\City|bool
    {
        if (!$model = $this->find($model, deleted: true)) return false;

        return $model->restore();
    }

    /**
     * @return \App\DataTables\Dashboard\CityDatatable|DataTable
     */
    public function datatable(): \App\DataTables\Dashboard\CityDatatable|DataTable
    {
        return app(\App\DataTables\Dashboard\CityDatatable::class);
    }

    /**
     * @param int|\App\Models\City $model
     * @param bool $status
     * @return bool
     */
    public function toggleStatus(int|\App\Models\City $model, bool $status = false): bool
    {
        $model = $this->find($model);

        return $model->update(compact('status'));
    }

    /**
     * @param int|\App\Models\City $model
     * @param array $data
     * @return \App\Models\City|bool
     */
    public function update(int|\App\Models\City $model, array $data): \App\Models\City|bool
    {
        if (!$model = $this->find($model)) return false;
        // changes something

        if ($locations  = $data['locations'] ?? null) {
            unset($data['locations']);
            $data['cities.locations'] = "ST_GeomFromText('{$locations}',0)";
        }

        if (!$model->update($data)) return false;
        // sync

        return $model;
    }

    /**
     * @return array|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function deletedOnly(): array|\Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->paginate();
    }
}
