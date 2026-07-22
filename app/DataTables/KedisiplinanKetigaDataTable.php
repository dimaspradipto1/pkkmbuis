<?php

namespace App\DataTables;

use App\Models\KedisiplinanKetiga;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KedisiplinanKetigaDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<KedisiplinanKetiga> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('checkbox', function($item){
                return '<input type="checkbox" class="record-checkbox" value="' . $item->id . '">';
            })
            ->addColumn('DT_RowIndex', '')
            ->addColumn('action', function($item){
                if (Auth::user()->role == 'mahasiswa') {
                    return '';
                }
                return '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="' . route('kedisiplinanketiga.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 rounded" ><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="' . route('kedisiplinanketiga.destroy', $item->id) . '" method="POST" style="display: inline">
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
                      ->orWhere('id_pendaftar', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['action', 'DT_RowIndex', 'checkbox']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<KedisiplinanKetiga>
     */
    public function query(KedisiplinanKetiga $model): QueryBuilder
    {
        $query = $model->newQuery()->with('user');
        
        if (Auth::user()->role == 'mahasiswa') {
            $query->where('user_id', Auth::id());
        } elseif (Auth::user()->role == 'kakakleting') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', Auth::id())->pluck('id');
            $query->whereHas('user', function($q) use ($myKelompokIds) {
                $q->whereIn('kelompok_id', $myKelompokIds);
            });
        }
        
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('kedisiplinanketiga-table')
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
            Column::make('checkbox')
                ->title('<input type="checkbox" id="select-all">')
                ->orderable(false)
                ->searchable(false)
                ->width(30)
                ->addClass('text-center'),
            Column::make('DT_RowIndex')
                ->title('NO')
                ->orderable(false)
                ->searchable(false),
            Column::make('user_name')
                ->title('Nama Pengguna'),
            Column::make('kelengkapan_atribut')
                ->title('Atribut'),
            Column::make('ketepatan_waktu')
                ->title('Waktu'),
            Column::make('perilaku')
                ->title('Perilaku'),
            Column::make('catatan')
                ->title('Catatan'),
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
        return 'KedisiplinanKetiga_' . date('YmdHis');
    }
}
