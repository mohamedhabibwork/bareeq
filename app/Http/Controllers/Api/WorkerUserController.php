<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerUser\StoreWorkerUserRequest;
use App\Http\Requests\WorkerUser\UpdateWorkerUserRequest;
use App\Http\Resources\WorkerUser\WorkerUserResource;
use App\Models\WorkerUser;
use App\Repository\WorkerUser\WorkerUserInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class WorkerUserController extends Controller
{
    private WorkerUserInterface $repository;

    public function __construct(WorkerUserInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return WorkerUserResource::collection($this->repository->index());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWorkerUserRequest $request
     * @return JsonResponse|WorkerUserResource
     */
    public function store(StoreWorkerUserRequest $request)
    {
        $validated = $request->validatedData();
        if (!$workerUser = $this->repository->store($validated)) {
            return ApiResponse::error(__('main.store_fail', ['model' => __('main.workerUser')]));
        }
        return new WorkerUserResource($workerUser);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return WorkerUserResource|JsonResponse|Response
     */
    public function show(int $id)
    {
        if (!$workerUser = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }
        return new WorkerUserResource($workerUser);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return WorkerUserResource|JsonResponse|Response
     */
    public function destroy(int $id)
    {
        if (!$workerUser = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($workerUser)) {
            return ApiResponse::error(__('main.delete_fail', ['model' => __('main.workerUser')]));
        }
        return ApiResponse::success(__('main.deleted_success', ['model' => __('main.workerUser')]));
    }

    public function startOrder(int $id, Request $request)
    {
        $validated = $request->validate([
            'after_images' => ['required', 'array', 'min:1'],
            'after_images.*' => ['sometimes', 'required', 'image'],
        ]);

        if (!$order = $this->repository->find($id))
            return ApiResponse::notFound();

        if ($request->user()->id !== $order->worker_id)
            return ApiResponse::error(__('main.order not available for you'), code: 403);

        $validated['order_status'] = WorkerUser::ORDER_STATUS['progress'];

        if (!$this->repository->update($order, $validated))
            return ApiResponse::error(__('main.start_fail', ['model' => __('main.workerUser')]));

        return ApiResponse::success(__('main.started_success', ['model' => __('main.workerUser')]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWorkerUserRequest $request
     * @param int $id
     * @return WorkerUserResource|JsonResponse|Response
     */
    public function update(UpdateWorkerUserRequest $request, int $id)
    {
        if (!$workerUser = $this->repository->find($id))
            return ApiResponse::notFound();

        $validated = $request->validatedData();
        if (!$workerUser = $this->repository->update($workerUser, $validated))
            return ApiResponse::error(__('main.update_fail', ['model' => __('main.workerUser')]));

        return new WorkerUserResource($workerUser);
    }
}
