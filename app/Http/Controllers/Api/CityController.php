<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\City\StoreCityRequest;
use App\Http\Requests\City\UpdateCityRequest;
use App\Http\Resources\City\CityResource;
use App\Repository\City\CityInterface;
use Illuminate\Http\Request;

class CityController extends Controller
{
    private CityInterface $repository;

    public function __construct(CityInterface $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        return $this->repository->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\City\StoreCityRequest  $request
     * @return CityResource|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $validated = $request->validatedData();
        if (!$city =$this->repository->store($validated)) {
            return  ApiResponse::error(__('main.store_fail',['model'=>__('main.city')]));
        }
        return new CityResource($city);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return CityResource|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if (!$city = $this->repository->find($id)) {
            return \App\Helpers\ApiResponse::notFound();
        }
        return new CityResource($city);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\City\UpdateCityRequest  $request
     * @param  int $id
     * @return CityResource|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, int $id)
    {
        if (!$city = $this->repository->find($id)) {
            return \App\Helpers\ApiResponse::notFound();
        }
        $validated = $request->validatedData();
        if (!$city =$this->repository->update($city,$validated)) {
            return  ApiResponse::error(__('main.update_fail',['model'=>__('main.city')]));
        }
        return new CityResource($city);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return CityResource|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (!$city = $this->repository->find($id)) {
            return \App\Helpers\ApiResponse::notFound();
        }

        if (!$this->repository->delete($city)) {
            return  ApiResponse::error(__('main.delete_fail',['model'=>__('main.city')]));
        }
        return  ApiResponse::success(__('main.deleted_success',['model'=>__('main.city')]));
    }


    public function checkArea(Request $request)
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
        ]);

        if (!$this->repository->checkArea($validated)) {
            return ApiResponse::error('not in area');
        }
        return ApiResponse::success('success');
    }
}
