<?php

namespace App\DataTables\Dashboard;

use App\Models\Car;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CarDatatable extends DataTable
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
            ->editColumn('created_at', fn($model) => $model->created_at->format('Y-m-d'))
            ->addColumn('action', 'dashboard::car.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param Car $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Car $model)
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
            ->setTableId('cardatatable-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('dashboard.cars.datatable'))
            ->dom('Bfrtip')
            ->orderBy(0)
            ->language(fileLangDatatable())
            ->buttons(
                Button::make('create')->action('window.location = "' . route('dashboard.cars.create') . '";')->text(__('main.create')),
                Button::make('colvis'), Button::make('print')->text(__('main.print')),
                Button::make('reset')->text(__('main.reset')),
                Button::make('reload')->text(__('main.reload')),
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
            Column::make('type')->title(__('main.type')),
            Column::make('color')->title(__('main.color')),
            Column::make('plate_number')->title(__('main.plate_number')),
            Column::make('image')->title(__('main.image')),
            Column::make('user_id')->title(__('main.user_id')),
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
        return 'Car_' . date('YmdHis');
    }
}
