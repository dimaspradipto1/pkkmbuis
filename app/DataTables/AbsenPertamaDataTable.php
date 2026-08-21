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
                      ->orWhere('id_pendaftar', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('bukti_izin', function($item) {
                if (!empty($item->bukti_izin)) {
                    return '<a href="' . asset('storage/' . $item->bukti_izin) . '" target="_blank" class="btn btn-sm btn-info text-white px-2 py-1 rounded"><i class="fa-solid fa-file-image me-1"></i> Bukti</a>';
                }
                return '-';
            })
            ->addColumn('waktu_datang', function($item) {
                return $item->waktu_datang
                    ? \Carbon\Carbon::parse($item->waktu_datang)->timezone('Asia/Jakarta')->format('H:i:s')
                    : '-';
            })
            ->addColumn('waktu_pulang', function($item) {
                return $item->waktu_pulang
                    ? \Carbon\Carbon::parse($item->waktu_pulang)->timezone('Asia/Jakarta')->format('H:i:s')
                    : '-';
            })
            ->addColumn('catatan_datang', function($item) {
                if (!empty($item->catatan_datang)) {
                    return e($item->catatan_datang);
                }
                if (!empty($item->catatan)) {
                    if (str_contains($item->catatan, 'Datang:')) {
                        preg_match('/Datang:\s*([^|]+)/', $item->catatan, $m);
                        return trim($m[1] ?? '-');
                    }
                    return e($item->catatan);
                }
                return '-';
            })
            ->addColumn('catatan_pulang', function($item) {
                if (!empty($item->catatan_pulang)) {
                    return e($item->catatan_pulang);
                }
                if (!empty($item->catatan) && str_contains($item->catatan, 'Pulang:')) {
                    preg_match('/Pulang:\s*([^|]+)/', $item->catatan, $m);
                    return trim($m[1] ?? '-');
                }
                return '-';
            })
            ->rawColumns(['action', 'DT_RowIndex', 'bukti_izin']);
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
                ->title('Hadir Datang'),
            Column::computed('waktu_datang')
                ->title('Waktu Datang')
                ->addClass('text-center'),
            Column::computed('catatan_datang')
                ->title('Catatan Datang')
                ->defaultContent('-'),
            Column::make('hadir_sore')
                ->title('Hadir Pulang'),
            Column::computed('waktu_pulang')
                ->title('Waktu Pulang')
                ->addClass('text-center'),
            Column::computed('catatan_pulang')
                ->title('Catatan Pulang')
                ->defaultContent('-'),
            Column::computed('bukti_izin')
                ->title('Bukti Izin')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
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
