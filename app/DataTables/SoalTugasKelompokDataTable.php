<?php

namespace App\DataTables;

use App\Models\SoalTugasKelompok;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SoalTugasKelompokDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('user_name', function ($item) {
                return $item->user->name ?? 'N/A';
            })
            ->addColumn('link_tugas_display', function ($item) {
                return '<a href="' . e($item->link_tugas) . '" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                        </a>';
            })
            ->addColumn('action', function ($item) {
                if (Auth::user()->role == 'mahasiswa') return '';
                return '
                    <div class="d-flex justify-content-center gap-1">
                        <form action="' . route('soaltugaskelompok.destroy', $item->id) . '" method="POST" style="display:inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger px-3 rounded" onclick="return confirm(\'Yakin ingin menghapus data ini?\')"><i class="fa-solid fa-trash"></i> Hapus</button>
                        </form>
                    </div>';
            })
            ->filterColumn('user_name', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->orderColumn('user_name', function ($query, $order) {
                $query->join('users', 'soal_tugas_kelompoks.user_id', '=', 'users.id')
                    ->orderBy('users.name', $order);
            })
            ->rawColumns(['action', 'link_tugas_display']);
    }

    public function query(SoalTugasKelompok $model): QueryBuilder
    {
        return $model->with('user')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('soaltugaskelompok-table')
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
        return [
            Column::make('DT_RowIndex')->title('NO')->orderable(false)->searchable(false),
            Column::make('user_name')->title('NAMA MAHASISWA'),
            Column::make('link_tugas')->title('LINK TUGAS'),
            Column::make('link_tugas_display')->title('AKSES')->orderable(false)->searchable(false),
            Column::computed('action')->title('AKSI')
                ->exportable(false)->printable(false)->width(100)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'SoalTugasKelompok_' . date('YmdHis');
    }
}
