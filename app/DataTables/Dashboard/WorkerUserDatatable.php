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
        return $model->newQuery();
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
                Button::make('create')->action('window.location = "' . route('dashboard.worker_users.create') . '";'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id','id')->title(__('main.id')),
            Column::make('user_id','user_id')->title(__('main.user_id')),
            Column::make('worker_id','worker_id')->title(__('main.worker_id')),
            Column::make('plan_id','plan_id')->title(__('main.plan_id')),
            Column::make('user_status','status')->title(__('main.status')),
            Column::make('order_status','status')->title(__('main.status')),
            Column::make('after_images','after_images')->title(__('main.after_images')),
            Column::make('before_images','before_images')->title(__('main.before_images')),
            Column::make('created_at','created_at')->title(__('main.created_at')),
            Column::computed('action')
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
