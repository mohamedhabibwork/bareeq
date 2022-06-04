<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerUser\StoreWorkerUserRequest;
use App\Http\Requests\WorkerUser\UpdateWorkerUserRequest;
use App\Http\Resources\WorkerUser\WorkerUserResource;
use App\Models\WorkerUser;
use App\Repository\User\UserInterface;
use App\Repository\WorkerUser\WorkerUserInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkerUserController extends Controller
{
    private WorkerUserInterface $repository;

    public function __construct(WorkerUserInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        //$data = $this->repository->index();
        $dataTable = $this->repository->datatable()->html();
        return view('dashboard::workerUser.index', compact('dataTable'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return WorkerUserResource|JsonResponse
     */
    public function datatable()
    {
        return $this->repository->datatable()->render('dashboard::workerUser.datatable');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(Request $request)
    {
        $data = $request->all();
        if (isset($data['user_id'])) {
            $user = app(UserInterface::class)->find($request->get('user_id'));
            $plan = $user?->selectPlan();
            abort_if(!$user || !$plan || !$user->availableOrderInPlan($plan), 404);
            $data['user_id'] = $user->id;
            $data['plan_id'] = $plan->id;
            $data['plan_type'] = $plan->getMorphClass();
        }
        $model = new WorkerUser($data);

        return view('dashboard::workerUser.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWorkerUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreWorkerUserRequest $request)
    {
        $validated = $request->validatedData();

        if (!$this->repository->store($validated)) {
            $this->alert('error', __('main.workerUser'), __('messages.not_save', ['model' => __('main.workerUser')]));
            return back();
        }
        $this->alert('success', __('main.workerUser'), __('messages.saved', ['model' => __('main.workerUser')]));
        if ($request->has('redirect')) {
            return  redirect()->to($request->get('redirect'));
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse|WorkerUserResource|JsonResponse
     */
    public function show(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.workerUser'), __('messages.not_found', ['model' => __('main.workerUser')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::workerUser.show', compact('model'));
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
            $this->alert('error', __('main.workerUser'), __('messages.not_found', ['model' => __('main.workerUser')]));
            return ApiResponse::notFound();
        }
        return view('dashboard::workerUser.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWorkerUserRequest $request
     * @param int $id
     * @return RedirectResponse|WorkerUserResource|JsonResponse
     */
    public function update(UpdateWorkerUserRequest $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.workerUser'), __('messages.not_found', ['model' => __('main.workerUser')]));
            return ApiResponse::notFound();
        }

        $validated = $request->validatedData();

        if (!$this->repository->update($model, $validated)) {
            $this->alert('error', __('main.workerUser'), __('messages.not_update', ['model' => __('main.workerUser')]));
            return back();
        }

        $this->alert('success', __('main.workerUser'), __('messages.updated', ['model' => __('main.workerUser')]));
        if ($request->has('redirect')) {
            return  redirect()->to($request->get('redirect'));
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse|WorkerUserResource|JsonResponse
     */
    public function destroy(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.workerUser'), __('messages.not_found', ['model' => __('main.workerUser')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->delete($model)) {
            $this->alert('error', __('main.workerUser'), __('messages.not_delete', ['model' => __('main.workerUser')]));
            return back();
        }
        $this->alert('success', __('main.workerUser'), __('messages.deleted', ['model' => __('main.workerUser')]));
        return back();
    }

    public function forceDelete(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.workerUser'), __('messages.not_found', ['model' => __('main.workerUser')]));
            return ApiResponse::notFound();
        }

        if (!$this->repository->forceDelete($model)) {
            $this->alert('error', __('main.workerUser'), __('messages.not_delete', ['model' => __('main.workerUser')]));

            return back();
        }
        $this->alert('success', __('main.workerUser'), __('messages.deleted', ['model' => __('main.workerUser')]));

        return back();
    }

    public function restore(int $id)
    {
        if (!$model = $this->repository->find($id, deleted: true)) {
            $this->alert('error', __('main.workerUser'), __('messages.not_found', ['model' => __('main.workerUser')]));
            return back();
        }

        if (!$this->repository->restore($model)) {
            $this->alert('error', __('main.workerUser'), __('messages.not_delete', ['model' => __('main.workerUser')]));
            return back();
        }

        $this->alert('success', __('main.workerUser'), __('messages.deleted', ['model' => __('main.workerUser')]));

        return back();
    }

    public function status(Request $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.workerUser'), __('messages.not_found', ['model' => __('main.workerUser')]));
            return ApiResponse::notFound();
        }

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        if (!$this->repository->toggleStatus($model, $request->boolean('status'))) {
            $this->alert('error', __('main.workerUser'), __('messages.status_not_update', ['model' => __('main.workerUser')]));

            return back();
        }

        $this->alert('success', __('main.workerUser'), __('messages.status_updated', ['model' => __('main.workerUser'), 'status' => $model->status]));

        return back();
    }

}
