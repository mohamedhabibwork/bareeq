<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SingleRequest\StoreSingleRequestRequest;
use App\Http\Requests\SingleRequest\UpdateSingleRequestRequest;
use App\Http\Resources\SingleRequest\SingleRequestResource;
use App\Repository\SingleRequest\SingleRequestInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SingleRequestController extends Controller
{
    private SingleRequestInterface $repository;

    public function __construct(SingleRequestInterface $repository)
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
     * @param StoreSingleRequestRequest $request
     * @return SingleRequestResource|JsonResponse|Response
     */
    public function store(StoreSingleRequestRequest $request)
    {
        $validated = $request->validatedData();
        if (!$singleRequest = $this->repository->store($validated)) {
            return ApiResponse::error(__('main.store_fail', ['model' => __('main.singleRequest')]));
        }
        return new SingleRequestResource($singleRequest);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return SingleRequestResource|JsonResponse|Response
     */
    public function show(int $id)
    {
        if (!$singleRequest = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }
        return new SingleRequestResource($singleRequest);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSingleRequestRequest $request
     * @param int $id
     * @return SingleRequestResource|JsonResponse|Response
     */
    public function update(UpdateSingleRequestRequest $request, int $id)
    {
        if (!$singleRequest = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }
        $validated = $request->validatedData();
        if (!$singleRequest = $this->repository->update($singleRequest, $validated)) {
            return ApiResponse::error(__('main.update_fail', ['model' => __('main.singleRequest')]));
        }
        return new SingleRequestResource($singleRequest);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return SingleRequestResource|JsonResponse|Response
     */
    public function destroy(int $id)
    {
        if (!$singleRequest = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($singleRequest)) {
            return ApiResponse::error(__('main.delete_fail', ['model' => __('main.singleRequest')]));
        }
        return ApiResponse::success(__('main.deleted_success', ['model' => __('main.singleRequest')]));
    }
}
