<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Models\Admin;
use App\Repository\Admin\AdminInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
     * @return JsonResponse
     */
    public function index()
    {
        //$data = $this->repository->index();
        $dataTable = $this->repository->datatable()->html();
        return view('dashboard::admin.index', compact('dataTable'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function datatable()
    {
        return $this->repository->datatable()->render('dashboard::admin.datatable');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|AdminResource|JsonResponse
     */
    public function create()
    {
        $model = new Admin();
        return view('dashboard::admin.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdminRequest $request
     * @return RedirectResponse|AdminResource|JsonResponse
     */
    public function store(StoreAdminRequest $request)
    {
        $validated = $request->validatedData();

        if (!$this->repository->store($validated)) {
            $this->alert('error', __('main.admin'), __('messages.not_save', ['model' => __('main.admin')]));
            return back();
        }

        $this->alert('success', __('main.admin'), __('messages.saved', ['model' => __('main.admin')]));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse|AdminResource|JsonResponse
     */
    public function show(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.admin'), __('messages.not_found', ['model' => __('main.admin')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::admin.show', compact('model'));
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
            $this->alert('error', __('main.admin'), __('messages.not_found', ['model' => __('main.admin')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::admin.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdminRequest $request
     * @param int $id
     * @return RedirectResponse|AdminResource|JsonResponse
     */
    public function update(UpdateAdminRequest $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.admin'), __('messages.not_found', ['model' => __('main.admin')]));
            return back();
        }

        $validated = $request->validatedData();

        if (!$this->repository->update($model, $validated)) {
            $this->alert('error', __('main.admin'), __('messages.not_update', ['model' => __('main.admin')]));
            return back();
        }

        $this->alert('success', __('main.admin'), __('messages.updated', ['model' => __('main.admin')]));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse|JsonResponse
     */
    public function destroy(Request $request,int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.admin'), __('messages.not_found', ['model' => __('main.admin')]));
            return ApiResponse::notFound();
        }
        if ($model->is($request->user())) {
            $this->alert('error', __('main.admin'), __('messages.not_delete', ['model' => __('main.admin')]));
            return back();
        }
        if (!$this->repository->delete($model)) {
            $this->alert('error', __('main.admin'), __('messages.not_delete', ['model' => __('main.admin')]));
            return back();
        }
        $this->alert('success', __('main.admin'), __('messages.deleted', ['model' => __('main.admin')]));
        return back();
    }

    public function forceDelete(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.admin'), __('messages.not_found', ['model' => __('main.admin')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->forceDelete($model)) {
            $this->alert('error', __('main.admin'), __('messages.not_delete', ['model' => __('main.admin')]));

            return back();
        }
        $this->alert('success', __('main.admin'), __('messages.deleted', ['model' => __('main.admin')]));

        return back();
    }

    public function restore(int $id)
    {
        if (!$model = $this->repository->find($id, deleted: true)) {
            $this->alert('error', __('main.admin'), __('messages.not_found', ['model' => __('main.admin')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->restore($model)) {
            $this->alert('error', __('main.admin'), __('messages.not_delete', ['model' => __('main.admin')]));
            return back();
        }

        $this->alert('success', __('main.admin'), __('messages.deleted', ['model' => __('main.admin')]));

        return back();
    }

    public function status(Request $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.admin'), __('messages.not_found', ['model' => __('main.admin')]));
            return ApiResponse::notFound();
        }

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        if (!$this->repository->toggleStatus($model, $request->boolean('status'))) {
            $this->alert('error', __('main.admin'), __('messages.status_not_update', ['model' => __('main.admin')]));

            return back();
        }

        $this->alert('success', __('main.admin'), __('messages.status_updated', ['model' => __('main.admin'), 'status' => $model->status]));

        return back();
    }

}
