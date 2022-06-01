<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Http\Resources\Car\CarResource;
use App\Repository\Car\CarInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CarController extends Controller
{
    private CarInterface $repository;

    public function __construct(CarInterface $repository)
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
     * @param StoreCarRequest $request
     * @return CarResource|JsonResponse|Response
     */
    public function store(StoreCarRequest $request)
    {
        $validated = $request->validatedData();
        if (!$car = $this->repository->store($validated)) {
            return ApiResponse::error(__('main.store_fail', ['model' => __('main.car')]));
        }
        return new CarResource($car);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return CarResource|JsonResponse|Response
     */
    public function show(int $id)
    {
        if (!$car = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }
        return new CarResource($car);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCarRequest $request
     * @param int $id
     * @return CarResource|JsonResponse|Response
     */
    public function update(UpdateCarRequest $request, int $id)
    {
        if (!$car = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }
        $validated = $request->validatedData();
        if (!$car = $this->repository->update($car, $validated)) {
            return ApiResponse::error(__('main.update_fail', ['model' => __('main.car')]));
        }
        return new CarResource($car);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return CarResource|JsonResponse|Response
     */
    public function destroy(int $id)
    {
        if (!$car = $this->repository->find($id)) {
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($car)) {
            return ApiResponse::error(__('main.delete_fail', ['model' => __('main.car')]));
        }
        return ApiResponse::success(__('main.deleted_success', ['model' => __('main.car')]));
    }
}
