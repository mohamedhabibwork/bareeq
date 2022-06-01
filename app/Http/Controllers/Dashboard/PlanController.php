<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\StorePlanRequest;
use App\Http\Requests\Plan\UpdatePlanRequest;
use App\Models\Plan;
use App\Repository\Plan\PlanInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    private PlanInterface $repository;

    public function __construct(PlanInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return PlanResource|JsonResponse
     */
    public function index()
    {
        //$data = $this->repository->index();
        $dataTable = $this->repository->datatable()->html();
        return view('dashboard::plan.index', compact('dataTable'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return PlanResource|JsonResponse
     */
    public function datatable()
    {
        return $this->repository->datatable()->render('dashboard::plan.datatable');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|PlanResource|JsonResponse
     */
    public function create()
    {
        $model = new Plan();
        return view('dashboard::plan.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePlanRequest $request
     * @return RedirectResponse|PlanResource|JsonResponse
     */
    public function store(StorePlanRequest $request)
    {
        $validated = $request->validatedData();

        if (!$this->repository->store($validated)) {
            $this->alert('error', __('main.plan'), __('messages.not_save', ['model' => __('main.plan')]));
            return back();
        }

        $this->alert('success', __('main.plan'), __('messages.saved', ['model' => __('main.plan')]));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse|PlanResource|JsonResponse
     */
    public function show(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.plan'), __('messages.not_found', ['model' => __('main.plan')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::plan.show', compact('model'));
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
            $this->alert('error', __('main.plan'), __('messages.not_found', ['model' => __('main.plan')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::plan.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePlanRequest $request
     * @param int $id
     * @return RedirectResponse|PlanResource|JsonResponse
     */
    public function update(UpdatePlanRequest $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.plan'), __('messages.not_found', ['model' => __('main.plan')]));
            return ApiResponse::notFound();
        }

        $validated = $request->validatedData();

        if (!$this->repository->update($model, $validated)) {
            $this->alert('error', __('main.plan'), __('messages.not_update', ['model' => __('main.plan')]));
            return back();
        }

        $this->alert('success', __('main.plan'), __('messages.updated', ['model' => __('main.plan')]));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse|PlanResource|JsonResponse
     */
    public function destroy(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.plan'), __('messages.not_found', ['model' => __('main.plan')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($model)) {
            $this->alert('error', __('main.plan'), __('messages.not_delete', ['model' => __('main.plan')]));
            return back();
        }
        $this->alert('success', __('main.plan'), __('messages.deleted', ['model' => __('main.plan')]));
        return back();
    }

    public function forceDelete(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.plan'), __('messages.not_found', ['model' => __('main.plan')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->forceDelete($model)) {
            $this->alert('error', __('main.plan'), __('messages.not_delete', ['model' => __('main.plan')]));

            return back();
        }
        $this->alert('success', __('main.plan'), __('messages.deleted', ['model' => __('main.plan')]));

        return back();
    }

    public function restore(int $id)
    {
        if (!$model = $this->repository->find($id, deleted: true)) {
            $this->alert('error', __('main.plan'), __('messages.not_found', ['model' => __('main.plan')]));
            return back();
        }

        if (!$this->repository->restore($model)) {
            $this->alert('error', __('main.plan'), __('messages.not_delete', ['model' => __('main.plan')]));
            return back();
        }

        $this->alert('success', __('main.plan'), __('messages.deleted', ['model' => __('main.plan')]));

        return back();
    }

    public function status(Request $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.plan'), __('messages.not_found', ['model' => __('main.plan')]));
            return ApiResponse::notFound();
        }

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        if (!$this->repository->toggleStatus($model, $request->boolean('status'))) {
            $this->alert('error', __('main.plan'), __('messages.status_not_update', ['model' => __('main.plan')]));

            return back();
        }

        $this->alert('success', __('main.plan'), __('messages.status_updated', ['model' => __('main.plan'), 'status' => $model->status]));

        return back();
    }

}
