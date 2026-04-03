<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RekapNilaiAkhirDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('score_tests', function ($row) {
                // M1-M4 Pre/Post (8 scores) + M5 Tugas (1 score)
                $testScores = $row->hasilTests->pluck('skor')->toArray();
                $tugasScore = ($row->tugasKelompok && !empty($row->tugasKelompok->link_tugas)) ? 100 : 0;

                $allScores = array_merge($testScores, [$tugasScore]);
                $avg = count($allScores) > 0 ? array_sum($allScores) / 9 : 0; // Divided by 9 metrics
                return round($avg, 2);
            })
            ->addColumn('score_absensi', function ($row) {
                // A1 P/S, A2 P/S, A3 P/S (Total 6)
                $points = 0;
                $absenRecords = [$row->absenPertama, $row->absenKedua, $row->absenKetiga];
                
                foreach ($absenRecords as $rec) {
                    if ($rec) {
                        // Check Pagi: ONLY count if status contains 'hadir' and isn't 'tidak hadir'
                        $pagi = strtolower($rec->hadir_pagi ?? '');
                        if ($pagi !== '' && str_contains($pagi, 'hadir') && !str_contains($pagi, 'tidak')) $points++;
                        
                        // Check Sore: ONLY count if status contains 'hadir' and isn't 'tidak hadir'
                        $sore = strtolower($rec->hadir_sore ?? '');
                        if ($sore !== '' && str_contains($sore, 'hadir') && !str_contains($sore, 'tidak')) $points++;
                    }
                }
                return round(($points / 6) * 100, 2);
            })
            ->addColumn('score_disiplin', function ($row) {
                // D1-D3 (Atribut, Waktu, Perilaku) -> Total 9 Metrics
                $points = 0;
                $records = [$row->kedisiplinanPertama, $row->kedisiplinanKedua, $row->kedisiplinanKetiga];
                
                foreach ($records as $rec) {
                    if ($rec) {
                        // Strict Matching: ONLY award if status is the highest honor
                        if (strtolower($rec->kelengkapan_atribut ?? '') === 'lengkap') $points++;
                        if (strtolower($rec->ketepatan_waktu ?? '') === 'tepat waktu') $points++;
                        if (strtolower($rec->perilaku ?? '') === 'sangat baik') $points++;
                    }
                }
                return round(($points / 9) * 100, 2);
            })
            ->addColumn('total_akhir', function ($row) {
                // Tests (20%), Absensi (50%), Disiplin (30%)
                
                // 1. Avg Tests (9 metrics: 8 pre/post + 1 tugas)
                $testScores = $row->hasilTests->pluck('skor')->toArray();
                $tugasScore = ($row->tugasKelompok && !empty($row->tugasKelompok->link_tugas)) ? 100 : 0;
                $scoreTests = (array_sum($testScores) + $tugasScore) / 9;

                // 2. Absensi Score (6 metrics) - Strict Presence Only
                $absPoints = 0;
                $absenRecords = [$row->absenPertama, $row->absenKedua, $row->absenKetiga];
                foreach ($absenRecords as $rec) {
                    if ($rec) {
                        $pagi = strtolower($rec->hadir_pagi ?? '');
                        if ($pagi !== '' && str_contains($pagi, 'hadir') && !str_contains($pagi, 'tidak')) $absPoints++;

                        $sore = strtolower($rec->hadir_sore ?? '');
                        if ($sore !== '' && str_contains($sore, 'hadir') && !str_contains($sore, 'tidak')) $absPoints++;
                    }
                }
                $scoreAbs = ($absPoints / 6) * 100;

                // 3. Disiplin Score (9 metrics) - Strict Honors Only
                $disPoints = 0;
                $disRecords = [$row->kedisiplinanPertama, $row->kedisiplinanKedua, $row->kedisiplinanKetiga];
                foreach ($disRecords as $rec) {
                    if ($rec) {
                        if (strtolower($rec->kelengkapan_atribut ?? '') === 'lengkap') $disPoints++;
                        if (strtolower($rec->ketepatan_waktu ?? '') === 'tepat waktu') $disPoints++;
                        if (strtolower($rec->perilaku ?? '') === 'sangat baik') $disPoints++;
                    }
                }
                $scoreDis = ($disPoints / 9) * 100;

                $total = ($scoreTests * 0.2) + ($scoreAbs * 0.5) + ($scoreDis * 0.3);
                return round($total, 2);
            })
            ->rawColumns(['total_akhir']);
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
            'tugasKelompok'
        ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('rekapnilaiakhir-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => route('rekapkeseluruhan.index'),
                'data' => 'function(d) { d.table = "rekapnilaiakhir"; }',
            ])
            ->orderBy(5, 'desc') // Change to DESC to see highest scores first
            ->selectStyleSingle()
            ->buttons(['excel', 'csv', 'pdf', 'print', 'reset', 'reload'])
            ->parameters([
                'scrollX' => true,
                'autoWidth' => false,
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('NO')->orderable(false)->searchable(false)->addClass('text-center'),
            Column::make('name')->title('NAMA MAHASISWA')->addClass('text-center'),
            Column::computed('score_tests')->title('TEST & TUGAS (20%)')->addClass('text-center'),
            Column::computed('score_absensi')->title('KEHADIRAN (50%)')->addClass('text-center'),
            Column::computed('score_disiplin')->title('KEDISIPLINAN (30%)')->addClass('text-center'),
            Column::computed('total_akhir')->title('TOTAL NILAI AKHIR')->addClass('text-center fw-bold text-primary'),
        ];
    }
}
