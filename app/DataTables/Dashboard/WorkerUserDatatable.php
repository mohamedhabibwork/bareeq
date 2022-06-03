<?php

namespace App\DataTables\Dashboard;

use App\Models\WorkerUser;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WorkerUserDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     * @throws \Yajra\DataTables\Exceptions\Exception
     * @throws \Yajra\DataTables\Exceptions\Exception
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at',fn($model)=>$model->created_at->format('Y-m-d'))
            ->addColumn('action', 'dashboard::workerUser.action')
            ->editColumn('after_images', fn(WorkerUser $wu) => view('actions.image', ['image' => $wu->after_images, 'id' => $wu->id]))
            ->editColumn('before_images', fn(WorkerUser $wu) => view('actions.image', ['image' => $wu->before_images, 'id' => $wu->id]));
    }

    /**
     * Get query source of dataTable.
     *
     * @param WorkerUser $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(WorkerUser $model)
    {
        return $model->query()->with(['user','plan','worker']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('workeruserdatatable-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('dashboard.worker_users.datatable'))
            ->dom('Bfrtip')
            ->orderBy(0)
            ->language(fileLangDatatable())
            ->buttons(
                Button::make('create')->action('window.location = "' . route('dashboard.worker_users.create') . '";')->text(__('main.create')),
                Button::make('colvis'),
                Button::make('print')->text(__('main.print')),
                Button::make('reset')->text(__('main.reset')),
                Button::make('reload')->text(__('main.reload')),
            );
    }

    /**
     * Get columns.
     * languid
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id','id')->title(__('main.id')),
            Column::make('user.name','user_id')->title(__('main.user_id')),
            Column::make('worker.name','worker.name')->render('full.worker?full.worker.name: "'.__('main.worker_not_selected').'"')->title(__('main.worker_id')),
            Column::make('plan.name','plan_id')->render('full.plan?full.plan.name: "'.__('main.no_plan').'"')->title(__('main.plan_id')),
            Column::make('user_status','status')->title(__('main.user_status')),
            Column::make('order_status','status')->title(__('main.order_status')),
            Column::make('after_images','after_images')->title(__('main.after_images')),
            Column::make('before_images','before_images')->title(__('main.before_images')),
            Column::make('created_at','created_at')->title(__('main.created_at')),
            Column::computed('action')
                ->title(__('main.action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'WorkerUser_' . date('YmdHis');
    }
}
