<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Http\Resources\Car\CarResource;
use App\Models\Car;
use App\Repository\Car\CarInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
     * @return CarResource|JsonResponse
     */
    public function index()
    {
        //$data = $this->repository->index();
        $dataTable = $this->repository->datatable()->html();
        return view('dashboard::car.index', compact('dataTable'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return CarResource|JsonResponse
     */
    public function datatable()
    {
        return $this->repository->datatable()->render('dashboard::car.datatable');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|CarResource|JsonResponse
     */
    public function create()
    {
        $model = new Car();
        return view('dashboard::car.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCarRequest $request
     * @return RedirectResponse|CarResource|JsonResponse
     */
    public function store(StoreCarRequest $request)
    {
        $validated = $request->validatedData();

        if (!$this->repository->store($validated)) {
            $this->alert('error', __('main.car'), __('messages.not_save', ['model' => __('main.car')]));
            return back();
        }

        $this->alert('success', __('main.car'), __('messages.saved', ['model' => __('main.car')]));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse|CarResource|JsonResponse
     */
    public function show(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.car'), __('messages.not_found', ['model' => __('main.car')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::car.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.car'), __('messages.not_found', ['model' => __('main.car')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::car.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCarRequest $request
     * @param int $id
     * @return RedirectResponse|CarResource|JsonResponse
     */
    public function update(UpdateCarRequest $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.car'), __('messages.not_found', ['model' => __('main.car')]));
            return ApiResponse::notFound();
        }

        $validated = $request->validatedData();

        if (!$this->repository->update($model, $validated)) {
            $this->alert('error', __('main.car'), __('messages.not_update', ['model' => __('main.car')]));
            return back();
        }

        $this->alert('success', __('main.car'), __('messages.updated', ['model' => __('main.car')]));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse|CarResource|JsonResponse
     */
    public function destroy(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.car'), __('messages.not_found', ['model' => __('main.car')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($model)) {
            $this->alert('error', __('main.car'), __('messages.not_delete', ['model' => __('main.car')]));
            return back();
        }
        $this->alert('success', __('main.car'), __('messages.deleted', ['model' => __('main.car')]));
        return back();
    }

    public function forceDelete(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.car'), __('messages.not_found', ['model' => __('main.car')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->forceDelete($model)) {
            $this->alert('error', __('main.car'), __('messages.not_delete', ['model' => __('main.car')]));

            return back();
        }
        $this->alert('success', __('main.car'), __('messages.deleted', ['model' => __('main.car')]));

        return back();
    }

    public function restore(int $id)
    {
        if (!$model = $this->repository->find($id, deleted: true)) {
            $this->alert('error', __('main.car'), __('messages.not_found', ['model' => __('main.car')]));
            return back();
        }

        if (!$this->repository->restore($model)) {
            $this->alert('error', __('main.car'), __('messages.not_delete', ['model' => __('main.car')]));
            return back();
        }

        $this->alert('success', __('main.car'), __('messages.deleted', ['model' => __('main.car')]));

        return back();
    }

    public function status(Request $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.car'), __('messages.not_found', ['model' => __('main.car')]));
            return ApiResponse::notFound();
        }

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        if (!$this->repository->toggleStatus($model, $request->boolean('status'))) {
            $this->alert('error', __('main.car'), __('messages.status_not_update', ['model' => __('main.car')]));

            return back();
        }

        $this->alert('success', __('main.car'), __('messages.status_updated', ['model' => __('main.car'), 'status' => $model->status]));

        return back();
    }

}
