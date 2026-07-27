<?php

namespace App\DataTables;

use App\Models\Lpj;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LpjDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Lpj> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->addColumn('lpj', function ($item) {
                return e(\Illuminate\Support\Str::limit($item->lpj, 150));
            })
            ->addColumn('action', function ($item) {
                return '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="' . route('lpj.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="' . route('lpj.destroy', $item->id) . '" method="POST" style="display: inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger px-3 rounded" onclick="return confirm(\'Yakin ingin menghapus LPJ ini?\')"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['action', 'DT_RowIndex']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Lpj>
     */
    public function query(Lpj $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('lpj-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload'),
                    ])->parameters([
                        'scrollX' => true,
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('NO')
                ->orderable(false)
                ->searchable(false),
            Column::make('lpj')
                ->title('LPJ'),
            Column::computed('action')
                ->title('AKSI')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Lpj_' . date('YmdHis');
    }
}
