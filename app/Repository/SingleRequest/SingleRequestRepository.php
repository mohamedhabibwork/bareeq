<?php

namespace App\Repository\SingleRequest;

use App\DataTables\Dashboard\SingleRequestDatatable;
use App\Events\Orders\OrderCreatedEvent;
use App\Models\SingleRequest;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerUser;
use App\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Services\DataTable;

/**
 * @property-read SingleRequest $model
 */
class SingleRequestRepository extends BaseRepository implements SingleRequestInterface
{
    protected array $filters = [];

    /**
     * @param SingleRequest $model
     */
    public function __construct(SingleRequest $model)
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
     * @return SingleRequest[]|Collection
     */
    public function all()
    {
        return $this->applyFilter($this->model->query())->get();
    }

    /**
     * @param array $data
     * @return SingleRequest|bool
     */
    public function store(array $data): SingleRequest|bool
    {
        // changes something

        if (!$saved = $this->model->create($data)) {
            return false;
        }
        // sync

        return $saved;
    }

    /**
     * @param int|int[]|SingleRequest $model
     * @return bool
     */
    public function destroy(int|array|SingleRequest $model): bool
    {
        if (!$model = $this->find($model)) return false;

        return $model instanceof Collection ? $model->toQuery()->delete() : $model->delete();
    }

    /**
     * @param int|SingleRequest $model
     * @param callable|null $callable
     * @param bool $deleted
     * @return SingleRequest|Collection|null
     */
    public function find(int|array|SingleRequest $model, callable $callable = null, bool $deleted = false): SingleRequest|Collection|null
    {
        if ($model instanceof SingleRequest) return $model;

        $query = $this->model->query();

        if ($deleted) $query->withTrashed();

        if (is_callable($callable)) $callable($query);

        if (is_array($model)) return $query->findMany($model);

        return $query->find($model);
    }

    /**
     * @param int|SingleRequest $model
     * @return SingleRequest|bool
     */
    public function delete(int|SingleRequest $model): SingleRequest|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->delete();
    }

    /**
     * @param int|SingleRequest $model
     * @return SingleRequest|bool
     */
    public function forceDelete(int|SingleRequest $model): SingleRequest|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->forceDelete();
    }

    /**
     * @param int|SingleRequest $model
     * @return SingleRequest|bool
     */
    public function restore(int|SingleRequest $model): SingleRequest|bool
    {
        if (!$model = $this->find($model, deleted: true)) return false;

        return $model->restore();
    }

    /**
     * @return SingleRequestDatatable|DataTable
     */
    public function datatable(): SingleRequestDatatable|DataTable
    {
        return app(SingleRequestDatatable::class);
    }

    /**
     * @param int|SingleRequest $model
     * @param bool $status
     * @return bool
     */
    public function toggleStatus(int|SingleRequest $model, bool $status = false): bool
    {
        $model = $this->find($model);

        return $model->update(compact('status'));
    }

    /**
     * @param int|SingleRequest $model
     * @param array $data
     * @return SingleRequest|bool
     */
    public function update(int|SingleRequest $model, array $data): SingleRequest|bool
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
     * @param SingleRequest $model
     * @param Worker $worker
     * @return WorkerUser
     */
    public function createOrder(SingleRequest $model,Worker $worker): WorkerUser
    {
        return tap($model->orders()->create(['user_id' => $model->user->id,'worker_id'=>$worker->id, 'order_status' => WorkerUser::ORDER_STATUS['pending'], 'user_status' => WorkerUser::USER_STATUS['pending']]),function (WorkerUser $workerUser) {
            event(new OrderCreatedEvent($workerUser));
        });
    }

    public function getDayOrders()
    {
        return $this->model->whereDoesntHave('orders')->paginate(pageName: 'requests')->withQueryString();
    }
}
