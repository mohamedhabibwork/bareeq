<?php

namespace App\Http\Controllers\Api;

use App\Events\Orders\OrderAcceptedEvent;
use App\Events\Orders\OrderRatedEvent;
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
use App\Notifications\Orders\OrderCreatedNotification;
use App\Repository\User\UserInterface;
use App\Repository\WorkerUser\WorkerUserInterface;
use DB;
use Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private UserInterface $repository;

    /**
     * @param UserInterface $repository
     */
    public function __construct(UserInterface $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param Request $request
     * @return UserResource|JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if ((!$user = $this->repository->login($request->get('phone'), $request->get('password'))) && !$user['token'])
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
        $token = $user['token']->plainTextToken;
        $user = $user['user'];
        event(new Registered($user));
        return (new UserResource($user))->additional(compact('token'));
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
        $user->loadMissing(['plans', 'car', 'city']);
        $user->loadCount('order_in_plan');
        $user->loadSum('plans', 'wishing_count');
        $user->withCasts(['plans_sum_wishing_count' => 'integer']);
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

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function orders(Request $request)
    {
        return WorkerUserResource::collection($this->repository->orders($request->user()));
    }

    /**
     * @param Request $request
     * @return WorkerUserResource|JsonResponse
     */
    public function storeOrder(Request $request)
    {
        $user = $request->user();
        if (!$plan = $user->selectPlan())
            return ApiResponse::error(__('your plan limited'));

        if (!$order = $this->repository->createOrder($user, $plan)) {
            return ApiResponse::error(__('main.your order failed'));
        }
        return new WorkerUserResource($order);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function subscribe(Request $request)
    {
        $request->validate(['plan_id' => ['required', 'integer', 'exists:plans,id']]);

        if (!$this->repository->subscribe($request->user(), $request->get('plan_id'))) {
            return ApiResponse::error(__('your order failed'));
        }
        return ApiResponse::success(__('main.plan subscribe successfully'));
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function singles(Request $request)
    {
        return SingleRequestResource::collection($this->repository->singles($request->user()));
    }

    /**
     * @param StoreSingleRequestRequest $request
     * @return SingleRequestResource|JsonResponse
     */
    public function createSingleRequest(StoreSingleRequestRequest $request)
    {
        if (!$single = $this->repository->createSingleRequest($request->user(), $request->validatedData())) {
            return ApiResponse::error(__('main.your request is failed'));
        }
        return new SingleRequestResource($single);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse|never
     */
    public function rateOrder(Request $request, int $id)
    {
        $orderInterface = app(WorkerUserInterface::class);

        if (!$order = $orderInterface->find($id)) {
            return ApiResponse::notFound();
        }

        if ($order->user_status != WorkerUser::USER_STATUS['success'] || $order->order_status != WorkerUser::ORDER_STATUS['success'] || $order->user_id != $request->user()->id || $order->rate != null) {
            return ApiResponse::error(__('main.order not available for you'), code: 403);
        }

        $validated = $request->validate([
            'rate' => ['required', 'integer', 'between:1,6'],
        ]);

        if (!$this->repository->rateOrder($order, (int)$validated['rate'])) {
            return ApiResponse::error(__('main.update_fail'));
        }
        event(new OrderRatedEvent($order));
        return ApiResponse::success(__('main.rated'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse|mixed|never
     */
    public function statusOrder(Request $request, int $id)
    {
        $orderInterface = app(WorkerUserInterface::class);

        if (!$order = $orderInterface->find($id)) {
            return ApiResponse::notFound();
        }

        if ($order->user_status != WorkerUser::USER_STATUS['pending'] || $order->user_id != $request->user()->id) {
            return ApiResponse::error(__('main.order not available for you'), code: 403);
        }

        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:2,3'],
        ]);

        return DB::transaction(function () use ($request, $order, $validated, $orderInterface) {
            if (!$orderInterface->update($order, ['user_status' => $validated['status']]))
                return ApiResponse::error(__('main.update_fail'));
            // if not changed
            if ($validated['status'] != WorkerUser::USER_STATUS['changed']) {
                event(new OrderAcceptedEvent($order));
                $request?->user()?->notifications()?->where('type', OrderCreatedNotification::class)?->where('data->id', $order->id)->first()?->markAsRead();
                return ApiResponse::success(__('main.accepted'));
            }

            $validated = $request->validate([
                'start_time' => ['sometimes', 'required', 'date_format:h:i'],
                'end_time' => ['sometimes', 'required', 'date_format:h:i', 'after_or_equal:start_time'],
                'wish_day' => ['sometimes', 'required', 'string', Rule::in(['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])],
            ]);

            if (!$this->repository->update($request->user(), $validated))
                return ApiResponse::error(__('main.update_fail'));
            $request?->user()?->notifications()?->where('type', OrderCreatedNotification::class)?->where('data->id', $order->id)->first()?->markAsRead();
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

        $user->loadMissing(['plans', 'car', 'city']);
        $user->loadCount('order_in_plan');
        $user->loadSum('plans', 'wishing_count');
        $user->withCasts(['plans_sum_wishing_count' => 'integer']);
        return new UserResource($user);
    }

    /**
     * @param Request $request
     * @return DatabaseNotificationCollection
     */
    public function notifications(Request $request)
    {
        return new DatabaseNotificationCollection($this->repository->notifications($request->user()));
    }

    /**
     * @param StoreCarRequest $request
     * @return CarResource|JsonResponse
     */
    public function attachCar(StoreCarRequest $request)
    {
        $validated = $request->validatedData();

        if (!$car = $this->repository->attachCar($request->user(), $validated)) {
            return ApiResponse::error(__('main.update_fail', ['model' => __('main.user')]));
        }
        return new CarResource($car);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function generateOTPCode(Request $request)
    {
        if (!$user = $request?->user()) {
            $request->validate(['phone'=>['required','string','max:13','exists:users']]);
            $user = $this->repository->findByPhone($request->get('phone', ''));
        }

        if (!$this->repository->generateOTPCode($user)) {
            return ApiResponse::error(__('main.update_fail', ['model' => __('main.user')]));
        }
        return ApiResponse::success(__('main.code_send'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'size:4'], 'password' => ['required', 'string', 'min:6', 'confirmed'], 'phone' => ['required', 'string', 'exists:users,phone']]);

        if (!$user = $this->repository->findByPhone($request->get('phone'))) {
            return ApiResponse::notFound();
        }

        if (!$this->repository->verifyOTPCode($user, $request->get('code'))) {
            return ApiResponse::error(__('main.not_verify', ['model' => __('main.user')]));
        }

        if (!$this->repository->resetPassword($user, $request->get('password'))) {
            return ApiResponse::error(__('main.not_reset', ['model' => __('main.user')]));
        }
        return ApiResponse::success(__('main.reset'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyOTPCode(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'size:4']]);
        if (!$this->repository->verifyOTPCode($request->user(), $request->get('code'))) {
            return ApiResponse::error(__('main.not_verify', ['model' => __('main.user')]));
        }
        return ApiResponse::success(__('main.verified'));
    }

    public function changePassword(Request $request)
    {
        $request->validate(['current_password' => ['required', 'string'], 'password' => ['required', 'string', 'min:6']]);

        if (!Hash::check($request->get('current_password'), $request->user()->getAuthPassword())) {
            return ApiResponse::error(__('main.not_match_password', ['model' => __('main.user')]));
        }

        if (!$this->repository->update($request->user(), ['password' => $request->get('password')])) {
            return ApiResponse::error(__('main.password_not_changed', ['model' => __('main.user')]));
        }
        return ApiResponse::success(__('main.password_changed'));
    }

    public function checkOTP(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'exists:users'],
            'code' => ['required', 'string', 'size:4', Rule::exists('users', 'otp_code')->where('phone', $request->get('phone'))],
        ]);

        return ApiResponse::success(__('main.otp_code_success'));
    }
}
