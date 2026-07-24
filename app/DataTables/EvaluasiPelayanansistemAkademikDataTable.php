<?php

namespace App\DataTables;

use App\Models\EvaluasiPelayanansistemAkademik;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EvaluasiPelayanansistemAkademikDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<EvaluasiPelayanansistemAkademik> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('user_name', function ($item) {
                return $item->user ? $item->user->name : '-';
            })
            ->addColumn('user_npm', function ($item) {
                return $item->user ? $item->user->id_pendaftar : '-';
            })
            ->addColumn('user_kelompok', function ($item) {
                return ($item->user && $item->user->kelompok) ? $item->user->kelompok->nama_kelompok : '-';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at ? $item->created_at->format('d-m-Y H:i') : '-';
            })
            ->addColumn('action', function ($item) {
                $showBtn = '<a href="' . route('evaluasipelayanansistemakademik.show', $item->id) . '" class="btn btn-sm btn-info text-white me-1" title="Detail"><i class="bi bi-eye"></i></a>';
                $editBtn = '<a href="' . route('evaluasipelayanansistemakademik.edit', $item->id) . '" class="btn btn-sm btn-primary me-1" title="Edit"><i class="bi bi-pencil"></i></a>';
                $deleteBtn = '';
                if (Auth::user()->role == 'admin') {
                    $deleteBtn = '<form action="' . route('evaluasipelayanansistemakademik.destroy', $item->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus evaluasi ini?\')">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                  </form>';
                }
                return '<div class="d-flex justify-content-center">' . $showBtn . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<EvaluasiPelayanansistemAkademik>
     */
    public function query(EvaluasiPelayanansistemAkademik $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['user.kelompok']);
        if (Auth::user()->role == 'mahasiswa') {
            $query->where('user_id', Auth::id());
        }
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('evaluasipelayanansistemakademik-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->parameters([
                        'dom' => 'Bfrtip',
                        'buttons' => ['excel', 'csv', 'pdf', 'print', 'reset', 'reload'],
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false)->width(50)->addClass('text-center'),
            Column::make('user_name')->title('Nama Mahasiswa'),
            Column::make('user_npm')->title('NPM / ID Pendaftar'),
            Column::make('user_kelompok')->title('Kelompok'),
            Column::make('created_at')->title('Waktu Mengisi'),
            Column::computed('action')
                  ->title('Aksi')
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
        return 'EvaluasiPelayanansistemAkademik_' . date('YmdHis');
    }
}
