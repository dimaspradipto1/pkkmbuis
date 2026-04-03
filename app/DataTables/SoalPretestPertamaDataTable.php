<?php

namespace App\DataTables;

use App\Models\SoalPretestPertama;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SoalPretestPertamaDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<SoalPretestPertama> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->addColumn('action', function ($item) {
                if (Auth::user()->role == 'mahasiswa') {
                    return '';
                }
                return '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="' . route('soalpretestpertama.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="' . route('soalpretestpertama.destroy', $item->id) . '" method="POST" style="display: inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger px-3 rounded" onclick="return confirm(\'Yakin ingin menghapus soal ini?\')"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['action', 'DT_RowIndex']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<SoalPretestPertama>
     */
    public function query(SoalPretestPertama $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('soalpretestpertama-table')
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
        $columns = [
            Column::make('DT_RowIndex')
                ->title('NO')
                ->orderable(false)
                ->searchable(false),
            Column::make('soal')
                ->title('Soal'),
            Column::make('pilihan_a')
                ->title('Pilihan A'),
            Column::make('pilihan_b')
                ->title('Pilihan B'),
            Column::make('pilihan_c')
                ->title('Pilihan C'),
            Column::make('pilihan_d')
                ->title('Pilihan D'),
            Column::make('jawaban')
                ->title('Jawaban'),
        ];

        if (Auth::user()->role != 'mahasiswa') {
            $columns[] = Column::computed('action')
                ->title('AKSI')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center');
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SoalPretestPertama_' . date('YmdHis');
    }
}
