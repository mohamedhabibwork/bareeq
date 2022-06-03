<?php

namespace App\DataTables\Dashboard;

use App\Models\User;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Exceptions\Exception;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     * @throws Exception
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at',fn($model)=>$model->created_at->format('Y-m-d'))
            ->addColumn('action', 'dashboard::user.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
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
            ->setTableId('userdatatable-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('dashboard.users.datatable'))
            ->dom('Bfrtip')
            ->orderBy(0)
            ->language(fileLangDatatable())
            ->buttons(
                Button::make('create')->action('window.location = "' . route('dashboard.users.create') . '";')->text(__('main.create')),
                Button::make('colvis'),
//                Button::make('export'),
//                Button::make('pdfHtml5'),
//                Button::make('csvHtml5'),
                Button::make('print')->text(__('main.print')),
//                Button::make('selectAll'),
//                Button::make('selectNone'),
                Button::make('reset')->text(__('main.reset')),
//                Button::make('json'),
//                Button::make('copy'),
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
            Column::make('name')->title(__('main.name')),
            Column::make('phone')->title(__('main.phone')),
//            Column::make('phone_verified_at'),
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
        return 'User_' . date('YmdHis');
    }
}
