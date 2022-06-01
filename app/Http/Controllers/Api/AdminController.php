<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Repository\Admin\AdminInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    private AdminInterface $repository;

    public function __construct(AdminInterface $repository)
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
     * @param StoreAdminRequest $request
     * @return AdminResource|JsonResponse|Response
     */
    public function store(StoreAdminRequest $request)
    {
        $validated = $request->validatedData();
        if (!$admin = $this->repository->store($validated)) {
            return ApiResponse::error(__('main.store_fail', ['model' => __('main.admin')]));
        }
        return new AdminResource($admin);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return AdminResource|JsonResponse|Response
     */
    public function show(int $id)
    {
        if (!$admin = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }
        return new AdminResource($admin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdminRequest $request
     * @param int $id
     * @return AdminResource|JsonResponse|Response
     */
    public function update(UpdateAdminRequest $request, int $id)
    {
        if (!$admin = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }
        $validated = $request->validatedData();
        if (!$admin = $this->repository->update($admin, $validated)) {
            return ApiResponse::error(__('main.update_fail', ['model' => __('main.admin')]));
        }
        return new AdminResource($admin);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return AdminResource|JsonResponse|Response
     */
    public function destroy(int $id)
    {
        if (!$admin = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($admin)) {
            return ApiResponse::error(__('main.delete_fail', ['model' => __('main.admin')]));
        }
        return ApiResponse::success(__('main.deleted_success', ['model' => __('main.admin')]));
    }
}
