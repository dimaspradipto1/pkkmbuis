<?php

namespace App\DataTables;

use App\Models\EvaluasiMenu;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KelulusanDataTable extends DataTable
{
    /**
     * Model class => nomor map used by the mahasiswa dashboard "Status Kelulusan" card.
     * Kept identical to resources/views/dashboard/index.blade.php so the admin table
     * and the student-facing card always agree.
     */
    protected array $evaluasiMap = [
        1  => \App\Models\EvaluasiPelayananKemahasiswaanPusatPrestasi::class,
        2  => \App\Models\EvaluasiPelayanansistemAkademik::class,
        3  => \App\Models\EvaluasiPelayanansistemAdministrasiKeuangan::class,
        4  => \App\Models\EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::class,
        5  => \App\Models\EvaluasiSistemPendidikanTinggidiIndonesia::class,
        6  => \App\Models\EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::class,
        7  => \App\Models\EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::class,
        8  => \App\Models\Perpustakaan::class,
        9  => \App\Models\EvaluasiIkaUis::class,
        10 => \App\Models\EvaluasiMotivasiGubernurKepulauanRiau::class,
        11 => \App\Models\EvaluasiFikes::class,
        12 => \App\Models\EvaluasiFst::class,
        13 => \App\Models\EvaluasiFeb::class,
    ];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $activeMenus = EvaluasiMenu::available()->where('is_active', true)->get();
        $requiredEvaluasiTotal = $activeMenus->count();

        // One query per active evaluasi model instead of one per student row.
        $completedUserIdSets = [];
        foreach ($activeMenus as $menu) {
            $modelClass = $menu->model_class;
            if ($modelClass) {
                $completedUserIdSets[] = $modelClass::pluck('user_id')->flip();
            }
        }

        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('absensi', function (User $row) {
                $count = 0;
                foreach ([$row->absenPertama, $row->absenKedua, $row->absenKetiga] as $ab) {
                    if ($ab) {
                        if (!empty($ab->hadir_pagi) && $ab->hadir_pagi !== 'Belum Absen') $count++;
                        if (!empty($ab->hadir_sore) && $ab->hadir_sore !== 'Belum Absen') $count++;
                    }
                }
                $complete = $count >= 6;
                return '<span class="badge ' . ($complete ? 'bg-success' : 'bg-warning text-dark') . ' rounded-pill">' . $count . '/6</span>';
            })
            ->addColumn('kedisiplinan', function (User $row) {
                $count = 0;
                foreach ([$row->kedisiplinanPertama, $row->kedisiplinanKedua, $row->kedisiplinanKetiga] as $di) {
                    if ($di && !empty($di->kelengkapan_atribut) && !empty($di->ketepatan_waktu) && !empty($di->perilaku)) {
                        $count++;
                    }
                }
                $complete = $count >= 3;
                return '<span class="badge ' . ($complete ? 'bg-success' : 'bg-warning text-dark') . ' rounded-pill">' . $count . '/3</span>';
            })
            ->addColumn('pretest', function (User $row) {
                $count = $row->hasilTests->where('type', 'pretest')->pluck('modul')->unique()->count();
                $complete = $count >= 4;
                return '<span class="badge ' . ($complete ? 'bg-success' : 'bg-warning text-dark') . ' rounded-pill">' . $count . '/4</span>';
            })
            ->addColumn('posttest', function (User $row) {
                $count = $row->hasilTests->where('type', 'posttest')->pluck('modul')->unique()->count();
                $complete = $count >= 4;
                return '<span class="badge ' . ($complete ? 'bg-success' : 'bg-warning text-dark') . ' rounded-pill">' . $count . '/4</span>';
            })
            ->addColumn('tugas', function (User $row) {
                $complete = (bool) $row->tugasKelompok;
                return '<span class="badge ' . ($complete ? 'bg-success' : 'bg-warning text-dark') . ' rounded-pill">' . ($complete ? '1' : '0') . '/1</span>';
            })
            ->addColumn('evaluasi', function (User $row) use ($completedUserIdSets, $requiredEvaluasiTotal) {
                $completed = 0;
                foreach ($completedUserIdSets as $set) {
                    if (isset($set[$row->id])) $completed++;
                }
                $complete = $requiredEvaluasiTotal === 0 || $completed >= $requiredEvaluasiTotal;
                return '<span class="badge ' . ($complete ? 'bg-success' : 'bg-warning text-dark') . ' rounded-pill">' . $completed . '/' . $requiredEvaluasiTotal . '</span>';
            })
            ->addColumn('status_kelulusan', function (User $row) use ($completedUserIdSets, $requiredEvaluasiTotal) {
                [$isAllComplete, $isPassed, $finalScore] = $this->computeStatus($row, $completedUserIdSets, $requiredEvaluasiTotal);
                $forced = !$isAllComplete && $row->kelulusan_is_active;

                if (!$isAllComplete && !$row->kelulusan_is_active) {
                    return '<span class="badge bg-secondary rounded-pill px-3 py-2">Belum Lengkap</span>';
                }

                $suffix = $forced ? ' <i class="bi bi-exclamation-triangle-fill" title="Dipaksa tampil, belum semua komponen lengkap"></i>' : '';

                return $isPassed
                    ? '<span class="badge bg-success rounded-pill px-3 py-2">Lulus (' . number_format($finalScore, 1) . ')' . $suffix . '</span>'
                    : '<span class="badge bg-danger rounded-pill px-3 py-2">Tidak Lulus (' . number_format($finalScore, 1) . ')' . $suffix . '</span>';
            })
            ->addColumn('kelulusan_is_active', function (User $row) {
                $statusBadge = $row->kelulusan_is_active
                    ? '<span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>'
                    : '<span class="badge bg-light text-dark border"><i class="bi bi-check-circle me-1"></i>Normal</span>';

                return '
                    <div class="d-flex flex-column align-items-center gap-1">
                        ' . $statusBadge . '
                        <form action="' . route('kelulusan.toggle', $row->id) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            <button type="submit" class="btn btn-sm ' . ($row->kelulusan_is_active ? 'btn-success' : 'btn-outline-secondary') . '">
                                ' . ($row->kelulusan_is_active
                                    ? '<i class="bi bi-toggle-on me-1"></i> Nonaktifkan'
                                    : '<i class="bi bi-toggle-off me-1"></i> Aktifkan') . '
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['absensi', 'kedisiplinan', 'pretest', 'posttest', 'tugas', 'evaluasi', 'status_kelulusan', 'kelulusan_is_active']);
    }

    /**
     * Mirrors the completeness/score logic in resources/views/dashboard/index.blade.php
     * so the admin table and the student-facing card never disagree.
     *
     * @return array{0: bool, 1: bool, 2: float}
     */
    protected function computeStatus(User $row, array $completedUserIdSets, int $requiredEvaluasiTotal): array
    {
        $absCount = 0;
        foreach ([$row->absenPertama, $row->absenKedua, $row->absenKetiga] as $ab) {
            if ($ab) {
                if (!empty($ab->hadir_pagi) && $ab->hadir_pagi !== 'Belum Absen') $absCount++;
                if (!empty($ab->hadir_sore) && $ab->hadir_sore !== 'Belum Absen') $absCount++;
            }
        }
        $absComplete = $absCount >= 6;

        $disPoints = 0;
        $disDayCount = 0;
        foreach ([$row->kedisiplinanPertama, $row->kedisiplinanKedua, $row->kedisiplinanKetiga] as $di) {
            if ($di && !empty($di->kelengkapan_atribut) && !empty($di->ketepatan_waktu) && !empty($di->perilaku)) {
                $disDayCount++;
            }
            if ($di) {
                if (strtolower($di->kelengkapan_atribut ?? '') === 'lengkap') $disPoints++;
                if (strtolower($di->ketepatan_waktu ?? '') === 'tepat waktu') $disPoints++;
                if (in_array(strtolower($di->perilaku ?? ''), ['baik', 'sangat baik'])) $disPoints++;
            }
        }
        $disComplete = $disDayCount >= 3;

        $pretestComplete = $row->hasilTests->where('type', 'pretest')->pluck('modul')->unique()->count() >= 4;
        $posttestComplete = $row->hasilTests->where('type', 'posttest')->pluck('modul')->unique()->count() >= 4;
        $tugasComplete = (bool) $row->tugasKelompok;

        $completedEvaluasi = 0;
        foreach ($completedUserIdSets as $set) {
            if (isset($set[$row->id])) $completedEvaluasi++;
        }
        $evaluasiComplete = $requiredEvaluasiTotal === 0 || $completedEvaluasi >= $requiredEvaluasiTotal;

        $isAllComplete = $absComplete && $disComplete && $pretestComplete && $posttestComplete && $tugasComplete && $evaluasiComplete;

        $sumTests = $row->hasilTests->sum('skor') + ($tugasComplete ? 100 : 0);
        $scoreTestsRaw = $sumTests / 9;
        $scoreAbsRaw = ($absCount / 6) * 100;
        $scoreDisRaw = ($disPoints / 9) * 100;

        $finalScore = $scoreTestsRaw * 0.2 + $scoreAbsRaw * 0.5 + $scoreDisRaw * 0.3;
        $isPassed = $finalScore >= 65 || (bool) $row->kelulusan_is_active;

        return [$isAllComplete, $isPassed, $finalScore];
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->where('role', 'mahasiswa')->with([
            'absenPertama',
            'absenKedua',
            'absenKetiga',
            'kedisiplinanPertama',
            'kedisiplinanKedua',
            'kedisiplinanKetiga',
            'hasilTests',
            'tugasKelompok',
        ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('kelulusan-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons(['excel', 'csv', 'pdf', 'print', 'reset', 'reload'])
            ->parameters([
                'scrollX' => true,
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('NO')->orderable(false)->searchable(false)->addClass('text-center'),
            Column::make('name')->title('Nama Mahasiswa'),
            Column::make('fakultas')->title('Fakultas'),
            Column::computed('absensi')->title('Absensi')->addClass('text-center'),
            Column::computed('kedisiplinan')->title('Kedisiplinan')->addClass('text-center'),
            Column::computed('pretest')->title('Pre-Test')->addClass('text-center'),
            Column::computed('posttest')->title('Post-Test')->addClass('text-center'),
            Column::computed('tugas')->title('Tugas Kelompok')->addClass('text-center'),
            Column::computed('evaluasi')->title('Evaluasi Materi')->addClass('text-center'),
            Column::computed('status_kelulusan')->title('Status Kelulusan')->addClass('text-center')->exportable(false)->printable(false),
            Column::computed('kelulusan_is_active')->title('Paksa Tampilkan Hasil')->addClass('text-center')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'StatusKelulusan_' . date('YmdHis');
    }
}
