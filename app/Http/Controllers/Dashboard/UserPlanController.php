<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserPlan\StoreUserPlanRequest;
use App\Http\Requests\UserPlan\UpdateUserPlanRequest;
use App\Models\UserPlan;
use App\Repository\UserPlan\UserPlanInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
     * @return UserPlanResource|JsonResponse
     */
    public function index()
    {
        //$data = $this->repository->index();
        $dataTable = $this->repository->datatable()->html();
        return view('dashboard::userPlan.index', compact('dataTable'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return UserPlanResource|JsonResponse
     */
    public function datatable()
    {
        return $this->repository->datatable()->render('dashboard::userPlan.datatable');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|UserPlanResource|JsonResponse
     */
    public function create()
    {
        $model = new UserPlan();
        return view('dashboard::userPlan.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserPlanRequest $request
     * @return RedirectResponse|UserPlanResource|JsonResponse
     */
    public function store(StoreUserPlanRequest $request)
    {
        $validated = $request->validatedData();

        if (!$this->repository->store($validated)) {
            $this->alert('error', __('main.userPlan'), __('messages.not_save', ['model' => __('main.userPlan')]));
            return back();
        }

        $this->alert('success', __('main.userPlan'), __('messages.saved', ['model' => __('main.userPlan')]));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse|UserPlanResource|JsonResponse
     */
    public function show(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.userPlan'), __('messages.not_found', ['model' => __('main.userPlan')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::userPlan.show', compact('model'));
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
            $this->alert('error', __('main.userPlan'), __('messages.not_found', ['model' => __('main.userPlan')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::userPlan.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserPlanRequest $request
     * @param int $id
     * @return RedirectResponse|UserPlanResource|JsonResponse
     */
    public function update(UpdateUserPlanRequest $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.userPlan'), __('messages.not_found', ['model' => __('main.userPlan')]));
            return ApiResponse::notFound();
        }

        $validated = $request->validatedData();

        if (!$this->repository->update($model, $validated)) {
            $this->alert('error', __('main.userPlan'), __('messages.not_update', ['model' => __('main.userPlan')]));
            return back();
        }

        $this->alert('success', __('main.userPlan'), __('messages.updated', ['model' => __('main.userPlan')]));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse|UserPlanResource|JsonResponse
     */
    public function destroy(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.userPlan'), __('messages.not_found', ['model' => __('main.userPlan')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($model)) {
            $this->alert('error', __('main.userPlan'), __('messages.not_delete', ['model' => __('main.userPlan')]));
            return back();
        }
        $this->alert('success', __('main.userPlan'), __('messages.deleted', ['model' => __('main.userPlan')]));
        return back();
    }

    public function forceDelete(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.userPlan'), __('messages.not_found', ['model' => __('main.userPlan')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->forceDelete($model)) {
            $this->alert('error', __('main.userPlan'), __('messages.not_delete', ['model' => __('main.userPlan')]));

            return back();
        }
        $this->alert('success', __('main.userPlan'), __('messages.deleted', ['model' => __('main.userPlan')]));

        return back();
    }

    public function restore(int $id)
    {
        if (!$model = $this->repository->find($id, deleted: true)) {
            $this->alert('error', __('main.userPlan'), __('messages.not_found', ['model' => __('main.userPlan')]));
            return back();
        }

        if (!$this->repository->restore($model)) {
            $this->alert('error', __('main.userPlan'), __('messages.not_delete', ['model' => __('main.userPlan')]));
            return back();
        }

        $this->alert('success', __('main.userPlan'), __('messages.deleted', ['model' => __('main.userPlan')]));

        return back();
    }

    public function status(Request $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.userPlan'), __('messages.not_found', ['model' => __('main.userPlan')]));
            return ApiResponse::notFound();
        }

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        if (!$this->repository->toggleStatus($model, $request->boolean('status'))) {
            $this->alert('error', __('main.userPlan'), __('messages.status_not_update', ['model' => __('main.userPlan')]));

            return back();
        }

        $this->alert('success', __('main.userPlan'), __('messages.status_updated', ['model' => __('main.userPlan'), 'status' => $model->status]));

        return back();
    }

}
