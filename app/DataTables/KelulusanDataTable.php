<?php

namespace App\DataTables;

use App\Models\EvaluasiMenu;
use App\Models\ModulSetting;
use App\Models\PostTestSetting;
use App\Models\PreTestSetting;
use App\Models\SertifikatSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KelulusanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $activeMenus = EvaluasiMenu::available()->where('is_active', true)->get();

        // One query per active evaluasi model keyed by menu ID.
        $completedUserIdSets = [];
        foreach ($activeMenus as $menu) {
            $modelClass = $menu->model_class;
            if ($modelClass) {
                $completedUserIdSets[$menu->id] = $modelClass::pluck('user_id')->flip();
            }
        }

        $activePreModules = PreTestSetting::getActiveModules();
        $totalActivePre = count($activePreModules);

        $activePostModules = PostTestSetting::getActiveModules();
        $totalActivePost = count($activePostModules);

        $isM5Active = ModulSetting::isActive(5);
        $sertifikatSetting = SertifikatSetting::current();

        $statusFilter = request('status_filter');
        if ($statusFilter && in_array($statusFilter, ['lulus', 'tidak_lulus', 'belum_lengkap'])) {
            $allStudents = (clone $query)->get();
            $matchedIds = [];
            foreach ($allStudents as $student) {
                [$isAllComplete, $isPassed, $finalScore] = $this->computeStatus($student, $completedUserIdSets, $activeMenus);
                if ($statusFilter === 'lulus' && $isAllComplete && $isPassed) {
                    $matchedIds[] = $student->id;
                } elseif ($statusFilter === 'tidak_lulus' && $isAllComplete && !$isPassed) {
                    $matchedIds[] = $student->id;
                } elseif ($statusFilter === 'belum_lengkap' && !$isAllComplete) {
                    $matchedIds[] = $student->id;
                }
            }
            $query->whereIn('users.id', $matchedIds);
        }

        return (new EloquentDataTable($query))
            ->filter(function ($query) {
                if (request()->has('search.value') && !empty(request('search.value'))) {
                    $search = request('search.value');
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('id_pendaftar', 'like', "%{$search}%")
                          ->orWhere('nim', 'like', "%{$search}%")
                          ->orWhere('program_studi', 'like', "%{$search}%")
                          ->orWhere('fakultas', 'like', "%{$search}%")
                          ->orWhereHas('kelompok', function($kq) use ($search) {
                              $kq->where('nama_kelompok', 'like', "%{$search}%");
                          });
                    });
                }
            })
            ->addIndexColumn()
            ->addColumn('name', function (User $row) {
                $prodi = $row->program_studi ?: ($row->fakultas ?: '-');
                $npm = $row->nim ?: ($row->id_pendaftar ?: '-');
                return '
                    <div class="d-flex flex-column text-start">
                        <span class="fw-bold text-dark fs-6" style="color: #0f172a !important;">' . e($row->name) . '</span>
                        <span class="text-muted extra-small" style="font-size: 0.76rem; letter-spacing: 0.2px;">
                            <span class="fw-semibold text-secondary">' . e($npm) . '</span> &bull; <span class="text-uppercase fw-semibold">' . e($prodi) . '</span>
                        </span>
                    </div>
                ';
            })
            ->addColumn('kelompok', function (User $row) {
                $namaKelompok = $row->kelompok ? $row->kelompok->nama_kelompok : '-';
                return '<span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold shadow-sm">' . e($namaKelompok) . '</span>';
            })
            ->addColumn('absensi', function (User $row) {
                $count = 0;
                foreach ([$row->absenPertama, $row->absenKedua, $row->absenKetiga] as $ab) {
                    if ($ab) {
                        if (!empty($ab->hadir_pagi) && $ab->hadir_pagi !== 'Belum Absen') $count++;
                        if (!empty($ab->hadir_sore) && $ab->hadir_sore !== 'Belum Absen') $count++;
                    }
                }
                $complete = $count >= 6;
                return '<span class="badge ' . ($complete ? 'bg-success text-white' : 'bg-warning text-dark') . ' rounded-pill px-2 py-1 text-nowrap">' . $count . '/6</span>';
            })
            ->addColumn('kedisiplinan', function (User $row) {
                $count = 0;
                foreach ([$row->kedisiplinanPertama, $row->kedisiplinanKedua, $row->kedisiplinanKetiga] as $di) {
                    if ($di && !empty($di->kelengkapan_atribut) && $di->kelengkapan_atribut !== '-'
                            && !empty($di->ketepatan_waktu) && $di->ketepatan_waktu !== '-'
                            && !empty($di->perilaku) && $di->perilaku !== '-') {
                        $count++;
                    }
                }
                $complete = $count >= 3;
                return '<span class="badge ' . ($complete ? 'bg-success text-white' : 'bg-warning text-dark') . ' rounded-pill px-2 py-1 text-nowrap">' . $count . '/3</span>';
            })
            ->addColumn('pretest', function (User $row) use ($activePreModules, $totalActivePre) {
                $count = $row->hasilTests->where('type', 'pretest')->whereIn('modul', $activePreModules)->pluck('modul')->unique()->count();
                $complete = ($totalActivePre === 0) || ($count >= $totalActivePre);
                return '<span class="badge ' . ($complete ? 'bg-success text-white' : 'bg-warning text-dark') . ' rounded-pill px-2 py-1 text-nowrap">' . $count . '/' . $totalActivePre . '</span>';
            })
            ->addColumn('posttest', function (User $row) use ($activePostModules, $totalActivePost) {
                $count = $row->hasilTests->where('type', 'posttest')->whereIn('modul', $activePostModules)->pluck('modul')->unique()->count();
                $complete = ($totalActivePost === 0) || ($count >= $totalActivePost);
                return '<span class="badge ' . ($complete ? 'bg-success text-white' : 'bg-warning text-dark') . ' rounded-pill px-2 py-1 text-nowrap">' . $count . '/' . $totalActivePost . '</span>';
            })
            ->addColumn('tugas', function (User $row) use ($isM5Active) {
                $complete = (bool) $row->tugasKelompok;
                if (!$isM5Active) {
                    return '<span class="badge bg-secondary text-white rounded-pill px-2 py-1 text-nowrap">Nonaktif</span>';
                }
                return '<span class="badge ' . ($complete ? 'bg-success text-white' : 'bg-warning text-dark') . ' rounded-pill px-2 py-1 text-nowrap">' . ($complete ? '1' : '0') . '/1</span>';
            })
            ->addColumn('evaluasi', function (User $row) use ($completedUserIdSets, $activeMenus) {
                $relevantMenus = $activeMenus->filter(fn($m) => $m->matchesUserFaculty($row));
                $requiredTotal = $relevantMenus->count();
                $completed = 0;
                foreach ($relevantMenus as $menu) {
                    if (isset($completedUserIdSets[$menu->id][$row->id])) {
                        $completed++;
                    }
                }
                $complete = $requiredTotal === 0 || $completed >= $requiredTotal;
                return '<span class="badge ' . ($complete ? 'bg-success text-white' : 'bg-warning text-dark') . ' rounded-pill px-2 py-1 text-nowrap">' . $completed . '/' . $requiredTotal . '</span>';
            })
            ->addColumn('status_kelulusan', function (User $row) use ($completedUserIdSets, $activeMenus) {
                [$isAllComplete, $isPassed, $finalScore] = $this->computeStatus($row, $completedUserIdSets, $activeMenus);

                if (!$isAllComplete) {
                    return '<span class="badge bg-secondary bg-opacity-75 rounded-pill px-3 py-2 text-white fw-semibold text-nowrap" style="background: #64748b !important;"><i class="bi bi-clock-history me-1 text-white"></i>Belum Lengkap</span>';
                }

                return $isPassed
                    ? '<span class="badge bg-success text-white rounded-pill px-3 py-2 fw-bold shadow-sm text-nowrap" style="background: #15803d !important;"><i class="bi bi-check-circle-fill me-1 text-white"></i>LULUS (' . number_format($finalScore, 1) . ')</span>'
                    : '<span class="badge bg-danger text-white rounded-pill px-3 py-2 fw-bold shadow-sm text-nowrap" style="background: #dc2626 !important;"><i class="bi bi-x-circle-fill me-1 text-white"></i>TIDAK LULUS (' . number_format($finalScore, 1) . ')</span>';
            })
            ->addColumn('nomor_sertifikat', function (User $row) use ($sertifikatSetting, $completedUserIdSets, $activeMenus) {
                [$isAllComplete, $isPassed, $finalScore] = $this->computeStatus($row, $completedUserIdSets, $activeMenus);

                if ($isAllComplete && $isPassed) {
                    if ($row->nomor_sertifikat) {
                        $fullNumber = '#' . str_pad($row->nomor_sertifikat, 4, '0', STR_PAD_LEFT) . '/' . $sertifikatSetting->kode_surat;
                        return '<span class="badge px-3 py-2 rounded-pill font-monospace fw-bold text-white shadow-sm text-nowrap" style="background: #15803d !important; font-size: 0.8rem; letter-spacing: 0.5px;">' . e($fullNumber) . '</span>';
                    } else {
                        return '<span class="badge bg-success bg-opacity-75 px-3 py-2 rounded-pill text-white fw-semibold text-nowrap" style="background: #15803d !important; font-size: 0.78rem;">Siap Terbit</span>';
                    }
                } else {
                    return '<span class="badge bg-secondary bg-opacity-75 px-3 py-2 rounded-pill text-white fw-semibold text-nowrap" style="background: #64748b !important; font-size: 0.78rem;">Belum Diterbitkan</span>';
                }
            })
            ->addColumn('sertifikat_action', function (User $row) use ($completedUserIdSets, $activeMenus) {
                [$isAllComplete, $isPassed, $finalScore] = $this->computeStatus($row, $completedUserIdSets, $activeMenus);

                if ($isAllComplete && $isPassed) {
                    return '
                        <button type="button" class="btn btn-sm btn-success text-white rounded-pill px-3 py-1 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center text-nowrap gap-1"
                                style="background: #15803d !important; border-color: #15803d !important; font-size: 0.82rem; min-width: 110px;"
                                onclick="downloadMahasiswaSertifikat(' . $row->id . ', this)">
                            <i class="bi bi-download text-white"></i> Unduh PNG
                        </button>
                    ';
                } else {
                    return '<span class="text-muted small">-</span>';
                }
            })
            ->addColumn('kelulusan_is_active', function (User $row) {
                $statusBadge = $row->kelulusan_is_active
                    ? '<span class="badge bg-success text-white mb-1 px-2 py-1 text-nowrap shadow-sm" style="background: #15803d !important;"><i class="bi bi-check-circle-fill me-1 text-white"></i>Aktif</span>'
                    : '<span class="badge bg-light text-secondary border mb-1 px-2 py-1 text-nowrap"><i class="bi bi-circle me-1"></i>Normal</span>';

                return '
                    <div class="d-flex flex-column align-items-center justify-content-center gap-1" style="min-width: 80px;">
                        ' . $statusBadge . '
                        <form action="' . route('kelulusan.toggle', $row->id) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            <button type="submit" class="btn btn-sm ' . ($row->kelulusan_is_active ? 'btn-outline-danger' : 'btn-outline-success') . ' rounded-pill px-2 py-1 text-nowrap fw-semibold" style="font-size: 0.75rem;">
                                ' . ($row->kelulusan_is_active
                                    ? '<i class="bi bi-toggle-on me-1"></i> Tutup'
                                    : '<i class="bi bi-toggle-off me-1"></i> Buka') . '
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['name', 'kelompok', 'absensi', 'kedisiplinan', 'pretest', 'posttest', 'tugas', 'evaluasi', 'status_kelulusan', 'nomor_sertifikat', 'sertifikat_action', 'kelulusan_is_active'])
            ->setRowId('id');
    }

    /**
     * Compute completion status, pass/fail result, and score.
     *
     * @return array{0: bool, 1: bool, 2: float}
     */
    protected function computeStatus(User $row, array $completedUserIdSets, $activeMenus): array
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
            if ($di && !empty($di->kelengkapan_atribut) && $di->kelengkapan_atribut !== '-'
                    && !empty($di->ketepatan_waktu) && $di->ketepatan_waktu !== '-'
                    && !empty($di->perilaku) && $di->perilaku !== '-') {
                $disDayCount++;
            }
            if ($di) {
                if (strtolower($di->kelengkapan_atribut ?? '') === 'lengkap') $disPoints++;
                if (strtolower($di->ketepatan_waktu ?? '') === 'tepat waktu') $disPoints++;
                if (in_array(strtolower($di->perilaku ?? ''), ['baik', 'sangat baik'])) $disPoints++;
            }
        }
        $disComplete = $disDayCount >= 3;

        $activePreModules = PreTestSetting::getActiveModules();
        $totalActivePre = count($activePreModules);

        $activePostModules = PostTestSetting::getActiveModules();
        $totalActivePost = count($activePostModules);

        $isM5Active = ModulSetting::isActive(5);

        $pretestCount = $row->hasilTests->where('type', 'pretest')->whereIn('modul', $activePreModules)->pluck('modul')->unique()->count();
        $pretestComplete = ($totalActivePre === 0) || ($pretestCount >= $totalActivePre);

        $posttestCount = $row->hasilTests->where('type', 'posttest')->whereIn('modul', $activePostModules)->pluck('modul')->unique()->count();
        $posttestComplete = ($totalActivePost === 0) || ($posttestCount >= $totalActivePost);

        $tugasComplete = !$isM5Active || (bool) $row->tugasKelompok;

        $relevantMenus = $activeMenus->filter(fn($m) => $m->matchesUserFaculty($row));
        $requiredEvaluasiTotal = $relevantMenus->count();
        $completedEvaluasi = 0;
        foreach ($relevantMenus as $menu) {
            if (isset($completedUserIdSets[$menu->id][$row->id])) {
                $completedEvaluasi++;
            }
        }
        $evaluasiComplete = $requiredEvaluasiTotal === 0 || $completedEvaluasi >= $requiredEvaluasiTotal;

        $isAllComplete = $absComplete && $disComplete && $pretestComplete && $posttestComplete && $tugasComplete && $evaluasiComplete;

        $sumPreTests = $row->hasilTests->where('type', 'pretest')->whereIn('modul', $activePreModules)->sum('skor');
        $sumPostTests = $row->hasilTests->where('type', 'posttest')->whereIn('modul', $activePostModules)->sum('skor');
        $sumTests = $sumPreTests + $sumPostTests + (($isM5Active && $tugasComplete) ? 100 : 0);
        $totalTestDenominator = $totalActivePre + $totalActivePost + ($isM5Active ? 1 : 0);
        $scoreTestsRaw = $totalTestDenominator > 0 ? ($sumTests / $totalTestDenominator) : 0;
        $scoreAbsRaw = ($absCount / 6) * 100;
        $scoreDisRaw = ($disPoints / 9) * 100;

        $finalScore = $scoreTestsRaw * 0.2 + $scoreAbsRaw * 0.5 + $scoreDisRaw * 0.3;
        
        // Seseorang hanya LULUS jika seluruh komponen lengkap DAN nilai >= 65
        $isPassed = $isAllComplete && ($finalScore >= 65);

        return [$isAllComplete, $isPassed, $finalScore];
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->where('role', 'mahasiswa')->with([
            'kelompok',
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
            ->ajax([
                'url' => route('kelulusan.index'),
                'data' => 'function(d) {
                    d.status_filter = $("#statusFilterSelect").val();
                }',
            ])
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons(['excel', 'csv', 'pdf', 'print', 'reset', 'reload'])
            ->parameters([
                'scrollX' => true,
                'pageLength' => 50,
                'language' => [
                    'search' => 'Cari nama mahasiswa:',
                    'searchPlaceholder' => 'Cari nama mahasiswa...',
                    'info' => 'Menampilkan _START_-_END_ dari _TOTAL_ data',
                    'infoEmpty' => 'Menampilkan 0 dari 0 data',
                    'lengthMenu' => 'Tampilkan _MENU_ data',
                    'paginate' => [
                        'first' => 'Awal',
                        'last' => 'Akhir',
                        'next' => 'Berikutnya',
                        'previous' => 'Sebelumnya',
                    ],
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('NO')->orderable(false)->searchable(false)->addClass('text-center align-middle'),
            Column::computed('name')->title('NAMA MAHASISWA')->addClass('align-middle'),
            Column::computed('kelompok')->title('KELOMPOK')->addClass('text-center align-middle'),
            Column::computed('absensi')->title('Absensi')->addClass('text-center align-middle'),
            Column::computed('kedisiplinan')->title('Kedisiplinan')->addClass('text-center align-middle'),
            Column::computed('pretest')->title('Pre-Test')->addClass('text-center align-middle'),
            Column::computed('posttest')->title('Post-Test')->addClass('text-center align-middle'),
            Column::computed('tugas')->title('Tugas')->addClass('text-center align-middle'),
            Column::computed('evaluasi')->title('Evaluasi')->addClass('text-center align-middle'),
            Column::computed('status_kelulusan')->title('STATUS KELULUSAN')->addClass('text-center align-middle')->exportable(false)->printable(false),
            Column::computed('nomor_sertifikat')->title('NO. SERTIFIKAT')->addClass('text-center align-middle'),
            Column::computed('sertifikat_action')->title('SERTIFIKAT PNG')->addClass('text-center align-middle')->exportable(false)->printable(false),
            Column::computed('kelulusan_is_active')->title('AKSI / BUKA')->addClass('text-center align-middle')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'StatusKelulusan_' . date('YmdHis');
    }
}

