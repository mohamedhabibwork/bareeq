<?php

namespace App\DataTables\Dashboard;

use App\Models\SingleRequest;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SingleRequestDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at',fn($model)=>$model->created_at->format('Y-m-d'))
            ->addColumn('action', 'dashboard::singleRequest.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param SingleRequest $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SingleRequest $model)
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
            ->setTableId('singlerequestdatatable-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('dashboard.singleRequest.datatable'))
            ->dom('Bfrtip')
            ->orderBy(0)
            ->language(fileLangDatatable())
            ->buttons(
                Button::make('create')->action('window.location = "' . route('dashboard.singleRequest.create') . '";'),
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
            Column::make('id')->title(__('main.id')),
            Column::make('car_name')->title(__('main.car_name')),
            Column::make('car_type')->title(__('main.car_type')),
            Column::make('phone')->title(__('main.phone')),
            Column::make('address')->title(__('main.address')),
            Column::make('car_area')->title(__('main.car_area')),
            Column::make('user_id')->title(__('main.user_id')),
            Column::make('created_at')->title(__('main.created_at')),
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
        return 'SingleRequest_' . date('YmdHis');
    }
}
