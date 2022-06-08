<?php

namespace App\Repository\User;

use App\DataTables\Dashboard\UserDatatable;
use App\Events\Orders\OrderCreatedEvent;
use App\Models\Car;
use App\Models\Plan;
use App\Models\SingleRequest;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerUser;
use App\Repository\BaseRepository;
use App\Repository\SingleRequest\SingleRequestRepository;
use App\Repository\Traits\AuthTrait;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\Paginator;
use LaravelIdea\Helper\App\Models\_IH_WorkerUser_C;
use Yajra\DataTables\Services\DataTable;

/**
 * @property-read User $model
 */
class UserRepository extends BaseRepository implements UserInterface
{
    use AuthTrait;

    protected array $filters = ['wish_day'];

    /**
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param string|null $day
     * @return LengthAwarePaginator
     */
    public function getDayUsers(?string $day = null): LengthAwarePaginator
    {
        $this->request->request->add(['wish_day' => mb_strtolower($day ?? date('l'))]);
        $query = $this->model
            ->has('plans')
            ->withSum('plans', 'wishing_count')
            ->withCount('orders')
            ->whereDoesntHave('orders', fn($q) => $q->whereDate('worker_user.created_at', today()))
            ->having('plans_sum_wishing_count', '>', 'orders_count');

        return $this->applyFilter($query)->paginate(pageName: 'orders')->withQueryString();
    }

    /**
     * @return Builder[]|Collection
     */
    public function all()
    {
        return $this->applyFilter($this->model->query())->get();
    }

    /**
     * @param array $data
     * @return array|bool
     */
    public function store(array $data): array|bool
    {
        // changes something

        if (!$user = $this->model->create($data)) {
            return false;
        }

        $token = $user->createToken(request()->ip());
        return compact('user', 'token');
    }

    /**
     * @param int|int[]|User $model
     * @return bool
     */
    public function destroy(int|array|User $model): bool
    {
        if (!$model = $this->find($model)) return false;

        return $model instanceof Collection ? $model->toQuery()->delete() : $model->delete();
    }

    /**
     * @param int|array|User $model
     * @param callable|null $callable
     * @param bool $deleted
     * @return User|Collection|null
     */
    public function find(int|array|User $model, callable $callable = null, bool $deleted = false): User|Collection|null
    {
        if ($model instanceof User) return $model;

        $query = $this->model->query();

        if ($deleted) $query->withTrashed();

        if (is_callable($callable)) $callable($query);

        if (is_array($model)) return $query->findMany($model);

        return $query->find($model);
    }

    /**
     * @param int|User $model
     * @return User|bool
     */
    public function delete(int|User $model): User|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->delete();
    }

    /**
     * @param int|User $model
     * @return User|bool
     */
    public function forceDelete(int|User $model): User|bool
    {
        if (!$model = $this->find($model)) return false;

        return $model->forceDelete();
    }

    /**
     * @param int|User $model
     * @return User|bool
     */
    public function restore(int|User $model): User|bool
    {
        if (!$model = $this->find($model, deleted: true)) return false;

        return $model->restore();
    }

    /**
     * @return UserDatatable|DataTable
     */
    public function datatable(): UserDatatable|DataTable
    {
        return app(UserDatatable::class);
    }

    /**
     * @param int|User $model
     * @param bool $status
     * @return bool
     */
    public function toggleStatus(int|User $model, bool $status = false): bool
    {
        $model = $this->find($model);

        return $model->update(compact('status'));
    }

    /**
     * @param int|User $model
     * @param array $data
     * @return User|bool
     */
    public function update(int|User $model, array $data): User|bool
    {
        if (!$model = $this->find($model)) return false;
        // changes something

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

    /**
     * @param User $user
     * @param Plan $plan
     * @return mixed
     */
    public function createOrder(User $user, Plan $plan)
    {
        return DB::transaction(function () use ($user, $plan) {
            return tap($plan->orders()->create(['user_id' => $user->id, 'user_status' => WorkerUser::USER_STATUS['success']]), function (WorkerUser $workerUser) {
                event(new OrderCreatedEvent($workerUser));
            });
        });
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Pagination\Paginator|Paginator|_IH_WorkerUser_C|Worker[]
     */
    public function orders(User $user)
    {
        return $user->orders()->with(['worker', 'plan'])->simplePaginate();
    }

    /**
     * @param User $user
     * @return Paginator|DatabaseNotification[]
     */
    public function notifications(User $user)
    {
        return $user->notifications()->simplePaginate();
    }

    /**
     * @param User $user
     * @param int|Plan $plan_id
     * @return bool
     */
    public function subscribe(User $user, Plan|int $plan_id)
    {
        $plan = $plan_id instanceof Plan ? $plan_id->id : $plan_id;
        $user->plans()->attach([$plan]);
        return true;
    }

    /**
     * @param User $user
     * @return \Illuminate\Pagination\LengthAwarePaginator|LengthAwarePaginator|SingleRequest[]
     */
    public function singles(User $user)
    {
        return app(SingleRequestRepository::class)->applyFilter($user->single_requests()->getQuery())->simplePaginate();
    }

    /**
     * @param User $user
     * @param array $validatedData
     * @return SingleRequest
     */
    public function createSingleRequest(User $user, array $validatedData): SingleRequest
    {
        return $user->single_requests()->create($validatedData);
    }

    /**
     * @param User $user
     * @param array $data
     * @return Car|Model
     */
    public function attachCar(User $user, array $data)
    {
        return $user->car()->firstOrCreate([], $data);
    }

    /**
     * @param WorkerUser $order
     * @param int $rate
     * @return bool
     */
    public function rateOrder(WorkerUser $order, int $rate): bool
    {
        return $order->forceFill(compact('rate'))->save();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function generateOTPCode(User $user): bool
    {
        return $user->forceFill(['otp_code' => rand(1000, 9999)])->save();
    }

    /**
     * @param User $user
     * @param string $code
     * @return bool
     */
    public function verifyOTPCode(User $user, string $code): bool
    {
        return $user->otp_code == $code && $user->update(['otp_code' => null, 'phone_verified_at' => now()]);
    }

    /**
     * @param User $user
     * @param string $password
     * @return bool
     */
    public function resetPassword(User $user, string $password)
    {
        return $user->update(compact('password') + ['otp_code' => null, 'phone_verified_at' => now()]);
    }

    /**
     * @param string $phone
     * @return User|null
     */
    public function findByPhone(string $phone): ?User
    {
        return $this->model->firstWhere('phone' , $phone);
    }


}
