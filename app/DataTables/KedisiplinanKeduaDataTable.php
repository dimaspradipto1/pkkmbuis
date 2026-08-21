<?php

namespace App\DataTables;

use App\Models\KedisiplinanKedua;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KedisiplinanKeduaDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<KedisiplinanKedua> $query Results from query() method.
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
                        <a href="' . route('kedisiplinankedua.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 rounded" ><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="' . route('kedisiplinankedua.destroy', $item->id) . '" method="POST" style="display: inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger px-3 rounded" onclick="return confirm(\'Yakin ingin menghapus data ini?\')"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->addColumn('user_name', function($item){
                $hasData = ($item->kelengkapan_atribut && $item->kelengkapan_atribut !== '-') ||
                           ($item->ketepatan_waktu && $item->ketepatan_waktu !== '-') ||
                           ($item->perilaku && $item->perilaku !== '-');
                $badge = $hasData 
                    ? '<span class="badge bg-success ms-2" title="Sudah Ada Data"><i class="fa-solid fa-circle-check"></i></span>' 
                    : '<span class="badge bg-secondary ms-2" title="Belum Ada Data"><i class="fa-solid fa-circle-xmark"></i></span>';
                return '<span>' . e($item->user->name ?? '-') . '</span>' . $badge;
            })
            ->editColumn('kelengkapan_atribut', function($item) {
                $val = $item->kelengkapan_atribut ?? '-';
                if ($val === 'Lengkap') {
                    return '<span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Lengkap</span>';
                } elseif ($val === 'Tidak Lengkap') {
                    return '<span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i>Tidak Lengkap</span>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })
            ->editColumn('ketepatan_waktu', function($item) {
                $val = $item->ketepatan_waktu ?? '-';
                if ($val === 'Tepat Waktu') {
                    return '<span class="badge bg-success"><i class="fa-solid fa-clock me-1"></i>Tepat Waktu</span>';
                } elseif ($val === 'Terlambat') {
                    return '<span class="badge bg-warning text-dark"><i class="fa-solid fa-triangle-exclamation me-1"></i>Terlambat</span>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })
            ->editColumn('perilaku', function($item) {
                $val = $item->perilaku ?? '-';
                if ($val === 'Baik') {
                    return '<span class="badge bg-success"><i class="fa-solid fa-thumbs-up me-1"></i>Baik</span>';
                } elseif ($val === 'Tidak Baik') {
                    return '<span class="badge bg-danger"><i class="fa-solid fa-thumbs-down me-1"></i>Tidak Baik</span>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })
            ->editColumn('catatan', function($item) {
                return e($item->catatan ?? '-');
            })
            ->filterColumn('user_name', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('id_pendaftar', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['action', 'DT_RowIndex', 'checkbox', 'user_name', 'kelengkapan_atribut', 'ketepatan_waktu', 'perilaku']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<KedisiplinanKedua>
     */
    public function query(KedisiplinanKedua $model): QueryBuilder
    {
        $query = $model->newQuery()->with('user');
        
        if (Auth::user()->role == 'mahasiswa') {
            $query->where('user_id', Auth::id());
        } elseif (Auth::user()->role == 'kakakpendamping') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', Auth::id())
                ->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', Auth::id()))
                ->pluck('id');
            $query->whereHas('user', function($q) use ($myKelompokIds) {
                $q->whereIn('kelompok_id', $myKelompokIds);
            });
        } elseif (Auth::user()->role == 'dosenpendamping') {
            $myKelompokIds = \App\Models\Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', Auth::id()))->pluck('id');
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
                    ->setTableId('kedisiplinankedua-table')
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
        $columns = [];

        if (in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
            $columns[] = Column::make('checkbox')
                ->title('<input type="checkbox" id="select-all">')
                ->orderable(false)
                ->searchable(false)
                ->width(30)
                ->addClass('text-center');
        }

        $columns[] = Column::make('DT_RowIndex')
            ->title('NO')
            ->orderable(false)
            ->searchable(false);
        $columns[] = Column::make('user_name')
            ->title('Nama Pengguna');
        $columns[] = Column::make('kelengkapan_atribut')
            ->title('Atribut');
        $columns[] = Column::make('ketepatan_waktu')
            ->title('Waktu');
        $columns[] = Column::make('perilaku')
            ->title('Perilaku');
        $columns[] = Column::make('catatan')
            ->title('Catatan');

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
        return 'KedisiplinanKedua_' . date('YmdHis');
    }
}
