<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\SingleRequest\StoreSingleRequestRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\Car\CarResource;
use App\Http\Resources\SingleRequest\SingleRequestResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\WorkerUser\WorkerUserResource;
use App\Models\WorkerUser;
use App\Repository\User\UserInterface;
use App\Repository\WorkerUser\WorkerUserInterface;
use DB;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private UserInterface $repository;

    public function __construct(UserInterface $repository)
    {
        $this->repository = $repository;
    }


    public function login(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if (!$user = $this->repository->login($request->get('phone'), $request->get('password')))
            return ApiResponse::error(__('auth.failed'));
        $token = $user['token']->plainTextToken;
        return (new UserResource($user['user']))->additional(compact('token'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return JsonResponse|UserResource
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validatedData();
        if (!$user = $this->repository->store($validated)) {
            return ApiResponse::error(__('main.store_fail', ['model' => __('main.user')]));
        }
        event(new Registered($user));
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return UserResource
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $user->loadMissing(['plans', 'car']);
        $user->loadCount('order_in_plan');
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return UserResource|JsonResponse|Response
     */
    public function destroy(int $id)
    {
        if (!$user = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($user)) {
            return ApiResponse::error(__('main.delete_fail', ['model' => __('main.user')]));
        }
        return ApiResponse::success(__('main.deleted_success', ['model' => __('main.user')]));
    }

    public function orders(Request $request)
    {
        return WorkerUserResource::collection($this->repository->orders($request->user()));
    }

    public function storeOrder(Request $request)
    {
        $user = $request->user();
        if (!$plan = $user->selectPlan()) {
            return ApiResponse::error(__('your plan limited'));
        }
        if (!$order = $this->repository->createOrder($user, $plan)) {
            return ApiResponse::error(__('main.your order failed'));
        }
        return new WorkerUserResource($order);
    }

    public function subscribe(Request $request)
    {
        $request->validate(['plan_id' => ['required', 'integer', 'exists:plans,id']]);

        if (!$this->repository->subscribe($request->user(), $request->get('plan_id'))) {
            return ApiResponse::error(__('your order failed'));
        }
        return ApiResponse::success(__('main.plan subscribe successfully'));
    }

    public function singles(Request $request)
    {
        return SingleRequestResource::collection($this->repository->singles($request->user()));
    }

    public function createSingleRequest(StoreSingleRequestRequest $request)
    {
        if (!$single = $this->repository->createSingleRequest($request->user(), $request->validatedData())) {
            return ApiResponse::error(__('main.your request is failed'));
        }
        return new SingleRequestResource($single);
    }

    public function statusOrder(Request $request, int $id)
    {
        $orderInterface = app(WorkerUserInterface::class);
        if (!$order = $orderInterface->find($id)) {
            return ApiResponse::notFound();
        }

        if (!$order->user_status != WorkerUser::USER_STATUS['pending'] || $order->user_id != $request->user()->id) {
            return ApiResponse::error(__('main.order not available for you'), code: 403);
        }

        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:2,3'],
        ]);

        return DB::transaction(function () use ($request, $order, $validated, $orderInterface) {
            if (!$orderInterface->update($order, ['user_status' => $validated['status'] ?? 3]))
                return ApiResponse::error(__('main.update_fail'));
            // if not changed
            if ($validated['status'] != 3) return ApiResponse::success(__('main.update'));

            unset($validated['status']);
            $request->validate([
                'start_time' => ['sometimes', 'required', 'date_format:h:i'],
                'end_time' => ['sometimes', 'required', 'date_format:h:i', 'after_or_equal:start_time'],
                'wish_day' => ['sometimes', 'required', 'string', Rule::in(['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])],
            ]);

            if (!$this->repository->update($request->user(), $validated))
                return ApiResponse::error(__('main.update_fail'));

            return ApiResponse::success(__('main.update'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @return JsonResponse|UserResource
     */
    public function update(UpdateUserRequest $request)
    {
        $validated = $request->validatedData();
        if (!$user = $this->repository->update($request->user(), $validated)) {
            return ApiResponse::error(__('main.update_fail', ['model' => __('main.user')]));
        }
        return new UserResource($user);
    }

    public function notifications(Request $request)
    {
        return WorkerUserResource::collection($this->repository->notifications($request->user()));
    }

    public function attachCar(StoreCarRequest $request)
    {
        $validated = $request->validatedData();

        if (!$car = $this->repository->attachCar($request->user(), $validated)) {
            return ApiResponse::error(__('main.update_fail', ['model' => __('main.user')]));
        }
        return new CarResource($car);
    }
}
