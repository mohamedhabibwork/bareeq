<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\StoreWorkerRequest;
use App\Http\Requests\Worker\UpdateWorkerRequest;
use App\Models\Worker;
use App\Repository\Worker\WorkerInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
     * @return WorkerResource|JsonResponse
     */
    public function index()
    {
        //$data = $this->repository->index();
        $dataTable = $this->repository->datatable()->html();
        return view('dashboard::worker.index', compact('dataTable'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return WorkerResource|JsonResponse
     */
    public function datatable()
    {
        return $this->repository->datatable()->render('dashboard::worker.datatable');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|WorkerResource|JsonResponse
     */
    public function create()
    {
        $model = new Worker();
        return view('dashboard::worker.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWorkerRequest $request
     * @return RedirectResponse|WorkerResource|JsonResponse
     */
    public function store(StoreWorkerRequest $request)
    {
        $validated = $request->validatedData();

        if (!$this->repository->store($validated)) {
            $this->alert('error', __('main.worker'), __('messages.not_save', ['model' => __('main.worker')]));
            return back();
        }

        $this->alert('success', __('main.worker'), __('messages.saved', ['model' => __('main.worker')]));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse|WorkerResource|JsonResponse
     */
    public function show(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.worker'), __('messages.not_found', ['model' => __('main.worker')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::worker.show', compact('model'));
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
            $this->alert('error', __('main.worker'), __('messages.not_found', ['model' => __('main.worker')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::worker.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWorkerRequest $request
     * @param int $id
     * @return RedirectResponse|WorkerResource|JsonResponse
     */
    public function update(UpdateWorkerRequest $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.worker'), __('messages.not_found', ['model' => __('main.worker')]));
            return ApiResponse::notFound();
        }

        $validated = $request->validatedData();

        if (!$this->repository->update($model, $validated)) {
            $this->alert('error', __('main.worker'), __('messages.not_update', ['model' => __('main.worker')]));
            return back();
        }

        $this->alert('success', __('main.worker'), __('messages.updated', ['model' => __('main.worker')]));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse|WorkerResource|JsonResponse
     */
    public function destroy(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.worker'), __('messages.not_found', ['model' => __('main.worker')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($model)) {
            $this->alert('error', __('main.worker'), __('messages.not_delete', ['model' => __('main.worker')]));
            return back();
        }
        $this->alert('success', __('main.worker'), __('messages.deleted', ['model' => __('main.worker')]));
        return back();
    }

    public function forceDelete(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.worker'), __('messages.not_found', ['model' => __('main.worker')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->forceDelete($model)) {
            $this->alert('error', __('main.worker'), __('messages.not_delete', ['model' => __('main.worker')]));

            return back();
        }
        $this->alert('success', __('main.worker'), __('messages.deleted', ['model' => __('main.worker')]));

        return back();
    }

    public function restore(int $id)
    {
        if (!$model = $this->repository->find($id, deleted: true)) {
            $this->alert('error', __('main.worker'), __('messages.not_found', ['model' => __('main.worker')]));
            return back();
        }

        if (!$this->repository->restore($model)) {
            $this->alert('error', __('main.worker'), __('messages.not_delete', ['model' => __('main.worker')]));
            return back();
        }

        $this->alert('success', __('main.worker'), __('messages.deleted', ['model' => __('main.worker')]));

        return back();
    }

    public function status(Request $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.worker'), __('messages.not_found', ['model' => __('main.worker')]));
            return ApiResponse::notFound();
        }

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        if (!$this->repository->toggleStatus($model, $request->boolean('status'))) {
            $this->alert('error', __('main.worker'), __('messages.status_not_update', ['model' => __('main.worker')]));

            return back();
        }

        $this->alert('success', __('main.worker'), __('messages.status_updated', ['model' => __('main.worker'), 'status' => $model->status]));

        return back();
    }

}
