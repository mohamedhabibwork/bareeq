<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\City\StoreCityRequest;
use App\Http\Requests\City\UpdateCityRequest;
use App\Models\City;
use App\Repository\City\CityInterface;

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
     * @return CityResource|\Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        return $this->repository->datatable()->render('dashboard::city.datatable');
    }

    /**
     * Display a listing of the resource.
     *
     * @return CityResource|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //$data = $this->repository->index();
        $dataTable = $this->repository->datatable()->html();
        return view('dashboard::city.index',compact('dataTable'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|CityResource|\Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $model = new City();
        return view('dashboard::city.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\City\StoreCityRequest $request
     * @return \Illuminate\Http\RedirectResponse|CityResource|\Illuminate\Http\JsonResponse
     */
    public function store(StoreCityRequest $request)
    {
        $validated = $request->validatedData();

        if (!$this->repository->store($validated)) {
            $this->alert('error', __('main.city'), __('messages.not_save', ['model' => __('main.city')]));
            return back();
        }

        $this->alert('success', __('main.city'), __('messages.saved', ['model' => __('main.city')]));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|CityResource|\Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.city'), __('messages.not_found', ['model' => __('main.city')]));
            return \App\Helpers\ApiResponse::notFound();
        }
        return view('dashboard::city.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.city'), __('messages.not_found', ['model' => __('main.city')]));
            return \App\Helpers\ApiResponse::notFound();
        }
        return view('dashboard::city.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\City\UpdateCityRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|CityResource|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateCityRequest $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.city'), __('messages.not_found', ['model' => __('main.city')]));
            return \App\Helpers\ApiResponse::notFound();
        }

        $validated = $request->validatedData();

        if (!$this->repository->update($model, $validated)) {
            $this->alert('error', __('main.city'), __('messages.not_update', ['model' => __('main.city')]));
            return back();
        }

        $this->alert('success', __('main.city'), __('messages.updated', ['model' => __('main.city')]));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|CityResource|\Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.city'), __('messages.not_found', ['model' => __('main.city')]));
            return \App\Helpers\ApiResponse::notFound();
        }

        if (!$this->repository->delete($model)) {
            $this->alert('error', __('main.city'), __('messages.not_delete', ['model' => __('main.city')]));
            return back();
        }
        $this->alert('success', __('main.city'), __('messages.deleted', ['model' => __('main.city')]));
        return back();
    }

    public function forceDelete(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.city'), __('messages.not_found', ['model' => __('main.city')]));
            return \App\Helpers\ApiResponse::notFound();
        }

        if (!$this->repository->forceDelete($model)) {
            $this->alert('error', __('main.city'), __('messages.not_delete', ['model' => __('main.city')]));

            return back();
        }
        $this->alert('success', __('main.city'), __('messages.deleted', ['model' => __('main.city')]));

        return back();
    }

    public function restore(int $id)
    {
        if (!$model = $this->repository->find($id, deleted: true)) {
            $this->alert('error', __('main.city'), __('messages.not_found', ['model' => __('main.city')]));
            return back();
        }

        if (!$this->repository->restore($model)) {
            $this->alert('error', __('main.city'), __('messages.not_delete', ['model' => __('main.city')]));
            return back();
        }

        $this->alert('success', __('main.city'), __('messages.deleted', ['model' => __('main.city')]));

        return back();
    }
    public function status(\Illuminate\Http\Request $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.city'), __('messages.not_found', ['model' => __('main.city')]));
            return \App\Helpers\ApiResponse::notFound();
        }

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        if (!$this->repository->toggleStatus($model, $request->boolean('status'))) {
            $this->alert('error', __('main.city'), __('messages.status_not_update', ['model' => __('main.city')]));

            return back();
        }

        $this->alert('success', __('main.city'), __('messages.status_updated', ['model' => __('main.city'), 'status' => $model->status]));

        return back();
    }

}
