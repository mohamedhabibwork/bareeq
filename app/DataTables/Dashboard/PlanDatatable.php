<?php

namespace App\DataTables\Dashboard;

use App\Models\Plan;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PlanDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     * @throws \Yajra\DataTables\Exceptions\Exception
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at',fn($model)=>$model->created_at->format('Y-m-d'))
            ->addColumn('action', 'dashboard::plan.action')
            ->editColumn('images', fn(Plan $plan) => view('actions.image', ['image' => $plan->images, 'id' => $plan->id]));;
    }

    /**
     * Get query source of dataTable.
     *
     * @param Plan $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Plan $model)
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
            ->setTableId('plandatatable-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('dashboard.plans.datatable'))
            ->dom('Bfrtip')
            ->orderBy(0)
            ->language(fileLangDatatable())
            ->buttons(
                Button::make('create')->action('window.location = "' . route('dashboard.plans.create') . '";')->text(__('main.create')),
                Button::make('colvis'),Button::make('print')->text(__('main.print')),
                Button::make('reset')->text(__('main.reset')),
                Button::make('reload')->text(__('main.reload')),
            );
    }

    /**
     * Get columns.
     *
     * @return array|Column[]
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->title(__('main.id')),
            Column::make('name')->title(__('main.name')),
            Column::make('price')->title(__('main.price')),
            Column::make('wishing_count')->title(__('main.wishing_count')),
            Column::make('status')->title(__('main.status')),
            Column::make('images')->title(__('main.images')),
            Column::make('created_at')->title(__('main.created_at')),
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
        return 'Plan_' . date('YmdHis');
    }
}
