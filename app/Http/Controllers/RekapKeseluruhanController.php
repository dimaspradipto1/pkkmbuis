<?php

namespace App\Http\Controllers;

use App\DataTables\RekapKeseluruhanDataTable;
use App\DataTables\RekapNilaiAkhirDataTable;
use App\Models\User;
use Illuminate\Http\Request;

class RekapKeseluruhanController extends Controller
{
    public function index(RekapKeseluruhanDataTable $dtDetailed, RekapNilaiAkhirDataTable $dtFinal)
    {
        if (request()->ajax()) {
            if (request()->get('table') === 'rekapnilaiakhir') {
                return $dtFinal->ajax();
            }
            if (request()->get('table') === 'rekapdetail') {
                return $dtDetailed->ajax();
            }
            // Fallback
            return $dtDetailed->ajax();
        }

        // Calculate KPI Summary Stats for Cards
        $students = User::where('role', 'mahasiswa')->with([
            'absenPertama', 'absenKedua', 'absenKetiga',
            'kedisiplinanPertama', 'kedisiplinanKedua', 'kedisiplinanKetiga',
            'hasilTests', 'tugasKelompok'
        ])->get();

        $totalMahasiswa = $students->count();
        $countTest = 0;
        $countTugas = 0;
        $countAbsensi = 0;
        $countDisiplin = 0;
        $passedCount = 0;

        $activePosttestModules = \App\Models\ModulSetting::getActivePosttestModules();
        $activePosttestCount = count($activePosttestModules);

        foreach ($students as $row) {
            // 1. Post-Test (Dinamis)
            $postTestScores = $row->hasilTests
                ->where('type', 'posttest')
                ->whereIn('modul', $activePosttestModules)
                ->pluck('skor')
                ->toArray();

            $scoreTes = ($activePosttestCount > 0 && count($postTestScores) > 0) ? (array_sum($postTestScores) / $activePosttestCount) : 0;
            if ($scoreTes > 0) {
                $countTest++;
            }

            // 2. Tugas Kelompok
            $scoreTugas = ($row->tugasKelompok && (!empty($row->tugasKelompok->link_tugas) || !empty($row->tugasKelompok->nilai))) ? ($row->tugasKelompok->nilai ?: 100) : 0;
            if ($scoreTugas > 0) {
                $countTugas++;
            }

            // 3. Absensi
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
            if ($absPoints > 0) {
                $countAbsensi++;
            }

            // 4. Kedisiplinan
            $disPoints = 0;
            $disRecords = [$row->kedisiplinanPertama, $row->kedisiplinanKedua, $row->kedisiplinanKetiga];
            foreach ($disRecords as $rec) {
                if ($rec) {
                    if (strtolower($rec->kelengkapan_atribut ?? '') === 'lengkap') $disPoints++;
                    if (strtolower($rec->ketepatan_waktu ?? '') === 'tepat waktu') $disPoints++;
                    if (in_array(strtolower($rec->perilaku ?? ''), ['baik', 'sangat baik'])) $disPoints++;
                }
            }
            $scoreDis = ($disPoints / 9) * 100;
            if ($disPoints > 0) {
                $countDisiplin++;
            }

            $totalAkhir = ($scoreTes * 0.1) + ($scoreTugas * 0.1) + ($scoreAbs * 0.5) + ($scoreDis * 0.3);

            if ($totalAkhir >= 65 || $row->kelulusan_is_active) {
                $passedCount++;
            }
        }

        $stats = [
            'totalMahasiswa' => $totalMahasiswa,
            'countTest'      => $countTest,
            'countTugas'     => $countTugas,
            'countAbsensi'   => $countAbsensi,
            'countDisiplin'  => $countDisiplin,
            'passedCount'    => $passedCount,
            'notPassedCount' => max(0, $totalMahasiswa - $passedCount),
            'pctTest'        => $totalMahasiswa > 0 ? round(($countTest / $totalMahasiswa) * 100, 1) : 0,
            'pctTugas'       => $totalMahasiswa > 0 ? round(($countTugas / $totalMahasiswa) * 100, 1) : 0,
            'pctAbsensi'     => $totalMahasiswa > 0 ? round(($countAbsensi / $totalMahasiswa) * 100, 1) : 0,
            'pctDisiplin'    => $totalMahasiswa > 0 ? round(($countDisiplin / $totalMahasiswa) * 100, 1) : 0,
            'passRate'       => $totalMahasiswa > 0 ? round(($passedCount / $totalMahasiswa) * 100, 1) : 0,
        ];

        return view('pages.rekapkeseluruhan.index', [
            'dtDetailed' => $dtDetailed->html(),
            'dtFinal'    => $dtFinal->html(),
            'stats'      => $stats,
        ]);
    }
}
