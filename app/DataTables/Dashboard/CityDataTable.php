<?php

namespace App\DataTables\Dashboard;

use App\Models\City;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CityDataTable extends DataTable
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
            ->addColumn('action', 'dashboard::city.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param City $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(City $model)
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
            ->setTableId('citydatatable-table')
            ->addTableClass(['text-center'])
            ->columns($this->getColumns())
            ->minifiedAjax(route('dashboard.cities.datatable'))
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons(
                Button::make('create')->action('window.location = "' . route('dashboard.cities.create') . '";')->text(__('main.create')),
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
            Column::make('name'),

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
        return 'City_' . date('YmdHis');
    }
}
