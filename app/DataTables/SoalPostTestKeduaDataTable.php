<?php

namespace App\DataTables;

use App\Models\SoalPostTestKedua;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SoalPostTestKeduaDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->addColumn('action', function ($item) {
                if (Auth::user()->role == 'mahasiswa') return '';
                return '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="' . route('soalposttestkedua.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="' . route('soalposttestkedua.destroy', $item->id) . '" method="POST" style="display:inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger px-3 rounded" onclick="return confirm(\'Yakin ingin menghapus soal ini?\')"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>';
            })
            ->rawColumns(['action', 'DT_RowIndex']);
    }

    public function query(SoalPostTestKedua $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('soalposttestkedua-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'), Button::make('csv'),
                Button::make('pdf'), Button::make('print'),
                Button::make('reset'), Button::make('reload'),
            ])->parameters(['scrollX' => true]);
    }

    public function getColumns(): array
    {
        $columns = [
            Column::make('DT_RowIndex')->title('NO')->orderable(false)->searchable(false),
            Column::make('soal')->title('Soal'),
            Column::make('pilihan_a')->title('Pilihan A'),
            Column::make('pilihan_b')->title('Pilihan B'),
            Column::make('pilihan_c')->title('Pilihan C'),
            Column::make('pilihan_d')->title('Pilihan D'),
            Column::make('jawaban')->title('Jawaban'),
        ];
        if (Auth::user()->role != 'mahasiswa') {
            $columns[] = Column::computed('action')->title('AKSI')
                ->exportable(false)->printable(false)->width(150)->addClass('text-center');
        }
        return $columns;
    }

    protected function filename(): string
    {
        return 'SoalPostTestKedua_' . date('YmdHis');
    }
}
