<?php

namespace App\DataTables;

use App\Models\MateriModul;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MateriModulDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->addColumn('modul1_file', fn($item) => $item->modul1
                ? '<a href="'.asset('storage/'.$item->modul1).'" target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-download me-1"></i>Download</a>'
                : '<span class="badge bg-secondary">Belum ada</span>')
            ->addColumn('modul2_file', fn($item) => $item->modul2
                ? '<a href="'.asset('storage/'.$item->modul2).'" target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-download me-1"></i>Download</a>'
                : '<span class="badge bg-secondary">Belum ada</span>')
            ->addColumn('modul3_file', fn($item) => $item->modul3
                ? '<a href="'.asset('storage/'.$item->modul3).'" target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-download me-1"></i>Download</a>'
                : '<span class="badge bg-secondary">Belum ada</span>')
            ->addColumn('modul4_file', fn($item) => $item->modul4
                ? '<a href="'.asset('storage/'.$item->modul4).'" target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-download me-1"></i>Download</a>'
                : '<span class="badge bg-secondary">Belum ada</span>')
            ->addColumn('modul5_file', fn($item) => $item->modul5
                ? '<a href="'.asset('storage/'.$item->modul5).'" target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-download me-1"></i>Download</a>'
                : '<span class="badge bg-secondary">Belum ada</span>')
            ->addColumn('action', function ($item) {
                if (Auth::user()->role == 'mahasiswa') return '';
                return '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="'.route('materimodul.edit', $item->id).'" class="btn btn-sm btn-warning text-white px-3 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="'.route('materimodul.destroy', $item->id).'" method="POST" style="display:inline">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger px-3 rounded" onclick="return confirm(\'Yakin ingin menghapus data dan semua file materi?\')"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>';
            })
            ->rawColumns(['action', 'DT_RowIndex', 'modul1_file', 'modul2_file', 'modul3_file', 'modul4_file', 'modul5_file']);
    }

    public function query(MateriModul $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('materimodul-table')
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
            Column::make('modul1_file')->title('Modul 1')->orderable(false)->searchable(false),
            Column::make('modul2_file')->title('Modul 2')->orderable(false)->searchable(false),
            Column::make('modul3_file')->title('Modul 3')->orderable(false)->searchable(false),
            Column::make('modul4_file')->title('Modul 4')->orderable(false)->searchable(false),
            Column::make('modul5_file')->title('Modul 5')->orderable(false)->searchable(false),
        ];

        if (Auth::user()->role != 'mahasiswa') {
            $columns[] = Column::computed('action')->title('AKSI')
                ->exportable(false)->printable(false)->width(120)->addClass('text-center');
        }

        return $columns;
    }

    protected function filename(): string
    {
        return 'MateriModul_' . date('YmdHis');
    }
}
