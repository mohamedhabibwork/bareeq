<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SingleRequest\StoreSingleRequestRequest;
use App\Http\Requests\SingleRequest\UpdateSingleRequestRequest;
use App\Models\SingleRequest;
use App\Repository\SingleRequest\SingleRequestInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
     * @return SingleRequestResource|JsonResponse
     */
    public function index()
    {
        //$data = $this->repository->index();
        $dataTable = $this->repository->datatable()->html();
        return view('dashboard::singleRequest.index', compact('dataTable'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return SingleRequestResource|JsonResponse
     */
    public function datatable()
    {
        return $this->repository->datatable()->render('dashboard::singleRequest.datatable');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|SingleRequestResource|JsonResponse
     */
    public function create()
    {
        $model = new SingleRequest();
        return view('dashboard::singleRequest.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSingleRequestRequest $request
     * @return RedirectResponse|SingleRequestResource|JsonResponse
     */
    public function store(StoreSingleRequestRequest $request)
    {
        $validated = $request->validatedData();

        if (!$this->repository->store($validated)) {
            $this->alert('error', __('main.singleRequest'), __('messages.not_save', ['model' => __('main.singleRequest')]));
            return back();
        }

        $this->alert('success', __('main.singleRequest'), __('messages.saved', ['model' => __('main.singleRequest')]));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse|SingleRequestResource|JsonResponse
     */
    public function show(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.singleRequest'), __('messages.not_found', ['model' => __('main.singleRequest')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::singleRequest.show', compact('model'));
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
            $this->alert('error', __('main.singleRequest'), __('messages.not_found', ['model' => __('main.singleRequest')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::singleRequest.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSingleRequestRequest $request
     * @param int $id
     * @return RedirectResponse|SingleRequestResource|JsonResponse
     */
    public function update(UpdateSingleRequestRequest $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.singleRequest'), __('messages.not_found', ['model' => __('main.singleRequest')]));
            return ApiResponse::notFound();
        }

        $validated = $request->validatedData();

        if (!$this->repository->update($model, $validated)) {
            $this->alert('error', __('main.singleRequest'), __('messages.not_update', ['model' => __('main.singleRequest')]));
            return back();
        }

        $this->alert('success', __('main.singleRequest'), __('messages.updated', ['model' => __('main.singleRequest')]));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse|SingleRequestResource|JsonResponse
     */
    public function destroy(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.singleRequest'), __('messages.not_found', ['model' => __('main.singleRequest')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($model)) {
            $this->alert('error', __('main.singleRequest'), __('messages.not_delete', ['model' => __('main.singleRequest')]));
            return back();
        }
        $this->alert('success', __('main.singleRequest'), __('messages.deleted', ['model' => __('main.singleRequest')]));
        return back();
    }

    public function forceDelete(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.singleRequest'), __('messages.not_found', ['model' => __('main.singleRequest')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->forceDelete($model)) {
            $this->alert('error', __('main.singleRequest'), __('messages.not_delete', ['model' => __('main.singleRequest')]));

            return back();
        }
        $this->alert('success', __('main.singleRequest'), __('messages.deleted', ['model' => __('main.singleRequest')]));

        return back();
    }

    public function restore(int $id)
    {
        if (!$model = $this->repository->find($id, deleted: true)) {
            $this->alert('error', __('main.singleRequest'), __('messages.not_found', ['model' => __('main.singleRequest')]));
            return back();
        }

        if (!$this->repository->restore($model)) {
            $this->alert('error', __('main.singleRequest'), __('messages.not_delete', ['model' => __('main.singleRequest')]));
            return back();
        }

        $this->alert('success', __('main.singleRequest'), __('messages.deleted', ['model' => __('main.singleRequest')]));

        return back();
    }

    public function status(Request $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.singleRequest'), __('messages.not_found', ['model' => __('main.singleRequest')]));
            return ApiResponse::notFound();
        }

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        if (!$this->repository->toggleStatus($model, $request->boolean('status'))) {
            $this->alert('error', __('main.singleRequest'), __('messages.status_not_update', ['model' => __('main.singleRequest')]));

            return back();
        }

        $this->alert('success', __('main.singleRequest'), __('messages.status_updated', ['model' => __('main.singleRequest'), 'status' => $model->status]));

        return back();
    }

}
