<?php

namespace App\DataTables;

use App\Models\AbsenPertama;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AbsenPertamaDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<AbsenPertama> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->addColumn('action', function($item){
                if (Auth::user()->role == 'mahasiswa') {
                    return '';
                }

                return '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="' . route('absenpertama.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 rounded" ><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="' . route('absenpertama.destroy', $item->id) . '" method="POST" style="display: inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger px-3 rounded" onclick="return confirm(\'Yakin ingin menghapus data ini?\')"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->addColumn('user_name', function($item){
                return $item->user->name;
            })
            ->filterColumn('user_name', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('nomor_registrasi', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['action', 'DT_RowIndex']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<AbsenPertama>
     */
    public function query(AbsenPertama $model): QueryBuilder
    {
        $query = $model->newQuery()->whereHas('user', function($q) {
            $q->where('role', 'mahasiswa');
        })->with('user');
        
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
                    ->setTableId('absenpertama-table')
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
                        Button::make('reload')
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
            Column::make('user_name')
                ->title('Nama Pengguna'),
            Column::make('hadir_pagi')
                ->title('Hadir Pagi'),
            Column::make('hadir_sore')
                ->title('Hadir Sore'),
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
        return 'AbsenPertama_' . date('YmdHis');
    }
}
