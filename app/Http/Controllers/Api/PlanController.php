<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\StorePlanRequest;
use App\Http\Requests\Plan\UpdatePlanRequest;
use App\Http\Resources\Plan\PlanResource;
use App\Repository\Plan\PlanInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PlanController extends Controller
{
    private PlanInterface $repository;

    public function __construct(PlanInterface $repository)
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
        return PlanResource::collection($this->repository->best());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePlanRequest $request
     * @return JsonResponse|PlanResource
     */
    public function store(StorePlanRequest $request)
    {
        $validated = $request->validatedData();
        if (!$plan = $this->repository->store($validated)) {
            return ApiResponse::error(__('main.store_fail', ['model' => __('main.plan')]));
        }
        return new PlanResource($plan);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return PlanResource|JsonResponse|Response
     */
    public function show(int $id)
    {
        if (!$plan = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }
        return new PlanResource($plan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePlanRequest $request
     * @param int $id
     * @return PlanResource|JsonResponse|Response
     */
    public function update(UpdatePlanRequest $request, int $id)
    {
        if (!$plan = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }
        $validated = $request->validatedData();
        if (!$plan = $this->repository->update($plan, $validated)) {
            return ApiResponse::error(__('main.update_fail', ['model' => __('main.plan')]));
        }
        return new PlanResource($plan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return PlanResource|JsonResponse|Response
     */
    public function destroy(int $id)
    {
        if (!$plan = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($plan)) {
            return ApiResponse::error(__('main.delete_fail', ['model' => __('main.plan')]));
        }
        return ApiResponse::success(__('main.deleted_success', ['model' => __('main.plan')]));
    }
}
