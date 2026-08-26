<?php

namespace App\DataTables;

use App\Models\EvaluasiFikes;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EvaluasiFikesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<EvaluasiFikes> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('checkbox', function ($item) {
                return '<input type="checkbox" class="record-checkbox" value="' . $item->id . '">';
            })
            ->addColumn('user_name', function ($item) {
                return $item->user ? $item->user->name : '-';
            })
            ->addColumn('user_npm', function ($item) {
                return $item->user ? $item->user->id_pendaftar : '-';
            })
            ->addColumn('user_fakultas', function ($item) {
                return $item->user ? ($item->user->fakultas ?: '-') : '-';
            })
            ->addColumn('user_kelompok', function ($item) {
                return ($item->user && $item->user->kelompok) ? $item->user->kelompok->nama_kelompok : '-';
            })
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('user_npm', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('id_pendaftar', 'like', "%{$keyword}%")
                        ->orWhere('nim', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('user_fakultas', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('fakultas', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('user_kelompok', function ($query, $keyword) {
                $query->whereHas('user.kelompok', function ($q) use ($keyword) {
                    $q->where('nama_kelompok', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at ? $item->created_at->format('d-m-Y H:i') : '-';
            })
            ->addColumn('action', function ($item) {
                $showBtn = '<a href="' . route('evaluasifikes.show', $item->id) . '" class="btn btn-sm btn-info text-white me-1" title="Detail"><i class="bi bi-eye"></i></a>';
                $editBtn = '<a href="' . route('evaluasifikes.edit', $item->id) . '" class="btn btn-sm btn-primary me-1" title="Edit"><i class="bi bi-pencil"></i></a>';
                $deleteBtn = '';
                if (Auth::user()->role == 'admin') {
                    $deleteBtn = '<form action="' . route('evaluasifikes.destroy', $item->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus evaluasi ini?\')">'
                                    . csrf_field() . method_field('DELETE') .
                                    '<button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                  </form>';
                }
                return '<div class="d-flex justify-content-center">' . $showBtn . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action', 'checkbox'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<EvaluasiFikes>
     */
    public function query(EvaluasiFikes $model): QueryBuilder
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
                    ->setTableId('evaluasifikes-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->parameters(['scrollX' => true]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [];

        if (Auth::user()->role != 'mahasiswa') {
            $columns[] = Column::make('checkbox')
                ->title('<input type="checkbox" id="select-all">')
                ->orderable(false)
                ->searchable(false)
                ->width(30)
                ->addClass('text-center');
        }

        $columns[] = Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false)->width(50)->addClass('text-center');
        $columns[] = Column::make('user_name')->title('Nama Mahasiswa');
        $columns[] = Column::make('user_npm')->title('NPM / ID Pendaftar');
        $columns[] = Column::make('user_fakultas')->title('Fakultas');
        $columns[] = Column::make('user_kelompok')->title('Kelompok');
        $columns[] = Column::make('created_at')->title('Waktu Mengisi');

        if (Auth::user()->role != 'mahasiswa') {
            $columns[] = Column::computed('action')
                  ->title('Aksi')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center');
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'EvaluasiFikes_' . date('YmdHis');
    }
}
