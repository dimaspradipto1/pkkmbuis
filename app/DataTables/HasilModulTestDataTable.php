<?php

namespace App\DataTables;

use App\Models\HasilTest;
use App\Models\Kelompok;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HasilModulTestDataTable extends DataTable
{
    protected ?string $type = null;
    protected ?int $modul = null;

    public function setTypeAndModul(string $type, int $modul): self
    {
        $this->type = $type;
        $this->modul = $modul;
        return $this;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('checkbox', function ($item) {
                return '<input type="checkbox" name="ids[]" value="' . $item->id . '" class="form-check-input record-checkbox">';
            })
            ->addColumn('user_name', function ($item) {
                return $item->user ? $item->user->name : '-';
            })
            ->addColumn('user_npm', function ($item) {
                return $item->user ? ($item->user->id_pendaftar ?? $item->user->nim ?? '-') : '-';
            })
            ->addColumn('user_kelompok', function ($item) {
                return ($item->user && $item->user->kelompok) ? $item->user->kelompok->nama_kelompok : '-';
            })
            ->editColumn('skor', function ($item) {
                $badge = $item->skor >= 65 ? 'bg-success' : 'bg-danger';
                $status = $item->skor >= 65 ? 'Tuntas' : 'Tidak Tuntas';
                return '<div class="d-flex flex-column align-items-center">
                            <span class="badge ' . $badge . ' fs-6 px-3 py-1 fw-bold shadow-sm">' . $item->skor . '</span>
                            <small class="text-muted extra-small mt-1">' . $status . '</small>
                        </div>';
            })
            ->addColumn('jawaban_summary', function ($item) {
                return '<span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-check2-circle text-success me-1"></i>' . $item->jumlah_benar . ' / ' . $item->total_soal . ' Benar</span>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') : '-';
            })
            ->addColumn('action', function ($item) {
                if (in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
                    $studentName = addslashes($item->user?->name ?? 'mahasiswa ini');
                    return '<form action="' . route('hasiltest.resetSingle', $item->id) . '" method="POST" class="d-inline reset-single-form" onsubmit="return confirm(\'Apakah Anda yakin ingin mereset nilai ' . $studentName . '? Mahasiswa akan dapat mengerjakan ulang test ini.\')">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger px-3 py-1 rounded shadow-sm" title="Reset Nilai">
                                    <i class="fa-solid fa-rotate-left me-1"></i> Reset
                                </button>
                            </form>';
                }
                return '-';
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
            ->filterColumn('user_kelompok', function ($query, $keyword) {
                $query->whereHas('user.kelompok', function ($q) use ($keyword) {
                    $q->where('nama_kelompok', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['checkbox', 'skor', 'jawaban_summary', 'action'])
            ->setRowId('id');
    }

    public function query(HasilTest $model): QueryBuilder
    {
        $type = $this->type ?? request()->route('type') ?? request()->get('type', 'pretest');
        $modul = (int) ($this->modul ?? request()->route('modul') ?? request()->get('modul', 1));

        $query = $model->newQuery()
            ->select('hasil_tests.*')
            ->where('hasil_tests.type', $type)
            ->where('hasil_tests.modul', $modul)
            ->with(['user.kelompok'])
            ->orderBy('hasil_tests.updated_at', 'desc');

        $authUser = Auth::user();
        if ($authUser && $authUser->role === 'kakakpendamping') {
            $myKelompokIds = Kelompok::where('pendamping_id', $authUser->id)
                ->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))
                ->pluck('id');
            $query->whereHas('user', fn($q) => $q->whereIn('kelompok_id', $myKelompokIds));
        } elseif ($authUser && $authUser->role === 'dosenpendamping') {
            $myKelompokIds = Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
            $query->whereHas('user', fn($q) => $q->whereIn('kelompok_id', $myKelompokIds));
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        $type = $this->type ?? request()->route('type') ?? request()->get('type', 'pretest');
        $modul = (int) ($this->modul ?? request()->route('modul') ?? request()->get('modul', 1));

        return $this->builder()
            ->setTableId('hasil-modul-test-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('hasiltest.modul', ['type' => $type, 'modul' => $modul]))
            ->selectStyleSingle()
            ->parameters([
                'scrollX' => true,
                'pageLength' => 25,
                'language' => ['search' => 'Cari:'],
            ]);
    }

    public function getColumns(): array
    {
        $columns = [];

        if (in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
            $columns[] = Column::make('checkbox')
                ->title('<input type="checkbox" id="select-all" class="form-check-input">')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false)
                ->width(40)
                ->addClass('text-center align-middle');
        }

        $columns[] = Column::make('DT_RowIndex')->title('No')->orderable(false)->searchable(false)->width(50)->addClass('text-center align-middle');
        $columns[] = Column::make('user_name')->title('Nama Mahasiswa')->orderable(false)->addClass('align-middle fw-semibold');
        $columns[] = Column::make('user_npm')->title('NPM / ID Pendaftar')->orderable(false)->addClass('align-middle');
        $columns[] = Column::make('user_kelompok')->title('Kelompok')->orderable(false)->addClass('align-middle');
        $columns[] = Column::make('skor')->title('Nilai / Skor')->orderable(true)->addClass('text-center align-middle');
        $columns[] = Column::make('jawaban_summary')->title('Hasil Soal')->orderable(false)->searchable(false)->addClass('text-center align-middle');
        $columns[] = Column::make('created_at')->title('Waktu Mengerjakan')->orderable(true)->addClass('text-center align-middle');

        if (in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
            $columns[] = Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(110)
                ->addClass('text-center align-middle');
        }

        return $columns;
    }

    protected function filename(): string
    {
        return 'Hasil_' . ucfirst($this->type ?? 'test') . '_Modul_' . ($this->modul ?? 1) . '_' . date('YmdHis');
    }
}
