<?php

namespace App\Repository\Worker;

use App\DataTables\Dashboard\WorkerDatatable;
use App\Models\Worker;
use App\Models\WorkerUser;
use App\Repository\BaseRepository;
use App\Repository\Traits\AuthTrait;
use App\Repository\WorkerUser\WorkerUserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @property-read Worker $model
 */
class WorkerRepository extends BaseRepository implements WorkerInterface
{
    use AuthTrait;

    protected array $filters = [];

    /**
     * @param Worker $model
     */
    public function __construct(Worker $model)
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
     * @return Worker[]|Collection
     */
    public function all()
    {
        return $this->applyFilter($this->model->query())->get();
    }

    /**
     * @param array $data
     * @return Worker|bool
     */
    public function store(array $data): Worker|bool
    {
        // changes something

        if (!$saved = $this->model->create($data)) {
            return false;
        }
        // sync

        return $saved;
    }

    /**
     * @param int|int[]|Worker $model
     * @return bool
     */
    public function destroy(int|array|Worker $model): bool
    {
        if (!$model = $this->find($model)) return false;

        return $model instanceof Collection ? $model->toQuery()->delete() : $model->delete();
    }

    /**
     * @param int|array|Worker $model
     * @param callable|null $callable
     * @param bool $deleted
     * @return Worker|Collection|null
     */
    public function find(int|array|Worker $model, callable $callable = null, bool $deleted = false): Worker|Collection|null
    {
        if ($model instanceof Worker) return $model;

        $query = $this->model->query();

        if ($deleted) $query->withTrashed();

        if (is_callable($callable)) $callable($query);

        if (is_array($model)) return $query->findMany($model);

        return $query->find($model);
    }

    /**
     * @param int|Worker $model
     * @return Worker|bool
     */
    public function delete(int|Worker $model): Worker|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->delete();
    }

    /**
     * @param int|Worker $model
     * @return Worker|bool
     */
    public function forceDelete(int|Worker $model): Worker|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->forceDelete();
    }

    /**
     * @param int|Worker $model
     * @return Worker|bool
     */
    public function restore(int|Worker $model): Worker|bool
    {
        if (!$model = $this->find($model, deleted: true)) return false;

        return $model->restore();
    }

    /**
     * @return WorkerDatatable|DataTable
     */
    public function datatable(): WorkerDatatable|DataTable
    {
        return app(WorkerDatatable::class);
    }

    /**
     * @param int|Worker $model
     * @param bool $status
     * @return bool
     */
    public function toggleStatus(int|Worker $model, bool $status = false): bool
    {
        $model = $this->find($model);

        return $model->update(compact('status'));
    }

    /**
     * @param int|Worker $model
     * @param array $data
     * @return Worker|bool
     */
    public function update(int|Worker $model, array $data): Worker|bool
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
     * @param Worker $worker
     * @return Paginator
     */
    public function orders(Worker $worker)
    {
        return app(WorkerUserRepository::class)
            ->applyFilter($worker->orders()->with(['user.car', 'plan'])
                ->latest()
                ->when($this->request->has('today'), fn($q, $v) => $q->whereDate('created_at', today()))
                ->getQuery())
            ->simplePaginate();
    }

    /**
     * @param Worker $worker
     * @param WorkerUser $order
     * @param array $after_images
     * @return boolean
     */
    public function finishOrder(Worker $worker, WorkerUser $order, array $after_images)
    {
        $order_status = WorkerUser::ORDER_STATUS['success'];
        return $order->update(compact('after_images', 'order_status'));
    }

    /**
     * @param Worker $worker
     * @return Paginator|DatabaseNotification[]
     */
    public function notifications(Worker $worker)
    {
        return $worker->notifications()->simplePaginate();
    }

}
