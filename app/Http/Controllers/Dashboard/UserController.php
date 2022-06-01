<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Repository\User\UserInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private UserInterface $repository;

    public function __construct(UserInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
//        $data = $this->repository->index();
        $dataTable = $this->repository->datatable()->html();
        return view('dashboard::user.index', compact('dataTable'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function datatable()
    {
        return $this->repository->datatable()->render('dashboard::user.datatable');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $model = new User();
        return view('dashboard::user.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return RedirectResponse|Response
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        if (!$this->repository->store($validated)) {
            $this->alert('error', __('main.user'), __('messages.not_save', ['model' => __('main.user')]));
            return back();
        }

        $this->alert('success', __('main.user'), __('messages.saved', ['model' => __('main.user')]));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse|Response
     */
    public function show(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.user'), __('messages.not_found', ['model' => __('main.user')]));
            return back();
        }

        return view('dashboard::user.show', compact('model'));
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
            $this->alert('error', __('main.user'), __('messages.not_found', ['model' => __('main.user')]));
            return back();
        }
        return view('dashboard::user.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.user'), __('messages.not_found', ['model' => __('main.user')]));
            return back();
        }

        $validated = $request->validated();

        if (!$this->repository->update($model, $validated)) {
            $this->alert('error', __('main.user'), __('messages.not_update', ['model' => __('main.user')]));
            return back();
        }

        $this->alert('success', __('main.user'), __('messages.updated', ['model' => __('main.user')]));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function destroy(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.user'), __('messages.not_found', ['model' => __('main.user')]));
            return back();
        }

        if (!$this->repository->delete($model)) {
            $this->alert('error', __('main.user'), __('messages.not_delete', ['model' => __('main.user')]));
            return back();
        }
        $this->alert('success', __('main.user'), __('messages.deleted', ['model' => __('main.user')]));
        return back();
    }

    public function forceDelete(int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.user'), __('messages.not_found', ['model' => __('main.user')]));
            return back();
        }

        if (!$this->repository->forceDelete($model)) {
            $this->alert('error', __('main.user'), __('messages.not_delete', ['model' => __('main.user')]));

            return back();
        }
        $this->alert('success', __('main.user'), __('messages.deleted', ['model' => __('main.user')]));

        return back();
    }

    public function restore(int $id)
    {
        if (!$model = $this->repository->find($id, deleted: true)) {
            $this->alert('error', __('main.user'), __('messages.not_found', ['model' => __('main.user')]));
            return back();
        }

        if (!$this->repository->restore($model)) {
            $this->alert('error', __('main.user'), __('messages.not_delete', ['model' => __('main.user')]));
            return back();
        }

        $this->alert('success', __('main.user'), __('messages.deleted', ['model' => __('main.user')]));

        return back();
    }

    public function status(Request $request, int $id)
    {
        if (!$model = $this->repository->find($id)) {
            $this->alert('error', __('main.user'), __('messages.not_found', ['model' => __('main.user')]));
            return back();
        }

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        if (!$this->repository->toggleStatus($model, $request->boolean('status'))) {
            $this->alert('error', __('main.user'), __('messages.status_not_update', ['model' => __('main.user')]));

            return back();
        }

        $this->alert('success', __('main.user'), __('messages.status_updated', ['model' => __('main.user'), 'status' => $model->status]));

        return back();
    }

}
