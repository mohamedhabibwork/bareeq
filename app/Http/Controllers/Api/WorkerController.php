<?php

namespace App\Http\Controllers\Api;

use App\Events\Orders\OrderHasFinishedEvent;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\StoreWorkerRequest;
use App\Http\Requests\Worker\UpdateWorkerRequest;
use App\Http\Resources\Worker\WorkerResource;
use App\Http\Resources\WorkerUser\WorkerUserResource;
use App\Models\WorkerUser;
use App\Repository\Worker\WorkerInterface;
use App\Repository\WorkerUser\WorkerUserInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WorkerController extends Controller
{
    private WorkerInterface $repository;

    public function __construct(WorkerInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index()
    {
        return $this->repository->index();
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);
        if (!$user = $this->repository->login($request->get('phone'), $request->get('password'))) {
            return ApiResponse::error(__('auth.failed'));
        }
        $token = $user['token']->plainTextToken;
        return (new WorkerResource($user['user']))->additional(compact('token'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWorkerRequest $request
     * @return JsonResponse|WorkerResource
     */
    public function store(StoreWorkerRequest $request)
    {
        $validated = $request->validatedData();
        if (!$worker = $this->repository->store($validated)) {
            return ApiResponse::error(__('main.store_fail', ['model' => __('main.worker')]));
        }
        return new WorkerResource($worker);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return WorkerResource
     */
    public function show(Request $request)
    {
        $worker = $request->user();
        return new WorkerResource($worker);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWorkerRequest $request
     * @return JsonResponse|WorkerResource
     */
    public function update(UpdateWorkerRequest $request)
    {
        $worker = $request->user();
        $validated = $request->validatedData();
        if (!$worker = $this->repository->update($worker, $validated)) {
            return ApiResponse::error(__('main.update_fail', ['model' => __('main.worker')]));
        }
        return new WorkerResource($worker);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return WorkerResource|JsonResponse|Response
     */
    public function destroy(int $id)
    {
        if (!$worker = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($worker))
            return ApiResponse::error(__('main.delete_fail', ['model' => __('main.worker')]));

        return ApiResponse::success(__('main.deleted_success', ['model' => __('main.worker')]));
    }

    public function orders(Request $request)
    {
        return WorkerUserResource::collection($this->repository->orders($request->user()));
    }

    public function finishOrder(Request $request, int $id)
    {
        if (!$order = app(WorkerUserInterface::class)->find($id)) {
            return ApiResponse::notFound();
        }

        if ($request->user()->id != $order->worker_id) {
            return ApiResponse::error(__('main.order not available for you'), code: 403);
        }

        if ($order->order_status == WorkerUser::ORDER_STATUS['success']) return ApiResponse::error(__('main.order has finish'));

        if ($order->order_status != WorkerUser::ORDER_STATUS['progress'])
            return ApiResponse::error(__('main.order not started yet'));

        $images = $request->validate(['after_images' => ['required', 'array'], 'after_images.*' => ['required', 'image']])['after_images'];
        if (!$this->repository->finishOrder($request->user(), $order, $images)) {
            return ApiResponse::error(__('main.order not finish'));
        }
        event(new OrderHasFinishedEvent($order));
        return ApiResponse::success(__('main.order has finish'));
    }


    public function changePassword(Request $request)
    {
        $request->validate(['current_password' => ['required', 'string'], 'password' => ['required', 'string', 'min:6']]);

        if (!\Hash::check($request->get('current_password'), $request->user()->getAuthPassword())) {
            return ApiResponse::error(__('main.not_match_password', ['model' => __('main.user')]));
        }

        if (!$this->repository->update($request->user(), ['password' => $request->get('password')])) {
            return ApiResponse::error(__('main.password_not_changed', ['model' => __('main.user')]));
        }
        return ApiResponse::success(__('main.password_changed'));
    }
}
