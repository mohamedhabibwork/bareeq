<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserPlan\StoreUserPlanRequest;
use App\Http\Requests\UserPlan\UpdateUserPlanRequest;
use App\Http\Resources\UserPlan\UserPlanResource;
use App\Repository\UserPlan\UserPlanInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserPlanController extends Controller
{
    private UserPlanInterface $repository;

    public function __construct(UserPlanInterface $repository)
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

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserPlanRequest $request
     * @return UserPlanResource|JsonResponse|Response
     */
    public function store(StoreUserPlanRequest $request)
    {
        $validated = $request->validatedData();
        if (!$userPlan = $this->repository->store($validated)) {
            return ApiResponse::error(__('main.store_fail', ['model' => __('main.userPlan')]));
        }
        return new UserPlanResource($userPlan);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return UserPlanResource|JsonResponse|Response
     */
    public function show(int $id)
    {
        if (!$userPlan = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }
        return new UserPlanResource($userPlan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserPlanRequest $request
     * @param int $id
     * @return UserPlanResource|JsonResponse|Response
     */
    public function update(UpdateUserPlanRequest $request, int $id)
    {
        if (!$userPlan = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }
        $validated = $request->validatedData();
        if (!$userPlan = $this->repository->update($userPlan, $validated)) {
            return ApiResponse::error(__('main.update_fail', ['model' => __('main.userPlan')]));
        }
        return new UserPlanResource($userPlan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return UserPlanResource|JsonResponse|Response
     */
    public function destroy(int $id)
    {
        if (!$userPlan = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($userPlan)) {
            return ApiResponse::error(__('main.delete_fail', ['model' => __('main.userPlan')]));
        }
        return ApiResponse::success(__('main.deleted_success', ['model' => __('main.userPlan')]));
    }
}
