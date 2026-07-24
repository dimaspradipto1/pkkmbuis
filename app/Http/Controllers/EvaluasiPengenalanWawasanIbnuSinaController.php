<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiPengenalanWawasanIbnuSinaDataTable;
use App\Http\Requests\EvaluasiPengenalanWawasanIbnuSinaRequest;
use App\Models\EvaluasiPengenalanWawasanIbnuSina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiPengenalanWawasanIbnuSinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiPengenalanWawasanIbnuSinaDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPengenalanWawasanIbnuSina::where('user_id', Auth::id())->first();
            $questions = EvaluasiPengenalanWawasanIbnuSina::questions();

            if ($evaluasi) {
                return view('pages.evaluasipengenalanwawasanibnusina.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasipengenalanwawasanibnusina.create', compact('questions'));
            }
        }

        $allResponses = EvaluasiPengenalanWawasanIbnuSina::with('user')->get();
        $totalRespondents = $allResponses->count();
        $questions = EvaluasiPengenalanWawasanIbnuSina::questions();

        // 1. Calculate Per-Question Overall Summary
        $rekapData = [];
        $sumNI = 0;

        foreach ($questions as $key => $questionText) {
            $avgScore = $totalRespondents > 0 ? (float) $allResponses->avg($key) : 0;
            $nik = $avgScore * 25; // Nilai Interval Konversi (%)

            if ($avgScore >= 3.26) {
                $mutu = 'A';
                $kategori = 'SANGAT BAIK';
                $badgeClass = 'bg-success';
            } elseif ($avgScore >= 3.0664) {
                $mutu = 'B';
                $kategori = 'BAIK';
                $badgeClass = 'bg-info';
            } elseif ($avgScore >= 2.60) {
                $mutu = 'C';
                $kategori = 'KURANG BAIK';
                $badgeClass = 'bg-warning text-dark';
            } else {
                $mutu = 'D';
                $kategori = 'TIDAK BAIK';
                $badgeClass = 'bg-danger';
            }

            $indicator = in_array($key, ['q1','q2','q3','q4','q5','q6','q7','q8']) ? 'Pemateri' : 'Materi';

            $rekapData[] = [
                'key' => $key,
                'indikator' => $indicator,
                'pertanyaan' => $questionText,
                'ni' => number_format($avgScore, 2),
                'ni_raw' => $avgScore,
                'nik' => number_format($nik, 2),
                'nik_raw' => $nik,
                'mutu' => $mutu,
                'kategori' => $kategori,
                'badgeClass' => $badgeClass,
            ];

            $sumNI += $avgScore;
        }

        $countQ = count($questions);
        $overallAvgNI = $countQ > 0 ? $sumNI / $countQ : 0;
        $overallNIK = $overallAvgNI * 25;

        if ($overallAvgNI >= 3.26) {
            $overallMutu = 'A';
            $overallKategori = 'SANGAT BAIK';
            $overallBadge = 'bg-success';
        } elseif ($overallAvgNI >= 3.0664) {
            $overallMutu = 'B';
            $overallKategori = 'BAIK';
            $overallBadge = 'bg-info';
        } elseif ($overallAvgNI >= 2.60) {
            $overallMutu = 'C';
            $overallKategori = 'KURANG BAIK';
            $overallBadge = 'bg-warning text-dark';
        } else {
            $overallMutu = 'D';
            $overallKategori = 'TIDAK BAIK';
            $overallBadge = 'bg-danger';
        }

        // 2. Faculty Breakdown Matrix (Dynamic Per Fakultas like Image 1)
        $defaultFaculties = [
            'FAKULTAS ILMU KESEHATAN (FIKes)',
            'FAKULTAS SAINS DAN TEKNOLOGI (FST)',
            'FAKULTAS EKONOMI DAN BISNIS (FEB)',
        ];

        $dbFaculties = \App\Models\User::whereNotNull('fakultas')
            ->where('fakultas', '!=', '')
            ->pluck('fakultas')
            ->unique()
            ->values()
            ->toArray();

        $facultyList = !empty($dbFaculties) ? array_unique(array_merge($defaultFaculties, $dbFaculties)) : $defaultFaculties;

        $facultyShortNames = [];
        foreach ($facultyList as $fac) {
            if (preg_match('/\((.*?)\)/', $fac, $matches)) {
                $facultyShortNames[$fac] = $matches[1];
            } else {
                $facultyShortNames[$fac] = $fac;
            }
        }

        $responsesByFaculty = [];
        foreach ($facultyList as $fac) {
            $responsesByFaculty[$fac] = $allResponses->filter(function($item) use ($fac) {
                return $item->user && $item->user->fakultas === $fac;
            });
        }

        $matrixFakultas = [];
        $sumFacultyTCR = array_fill_keys($facultyList, 0);

        foreach ($questions as $key => $questionText) {
            $rowTcr = [];
            $rowSum = 0;

            foreach ($facultyList as $fac) {
                $facGroup = $responsesByFaculty[$fac];
                $facCount = $facGroup->count();
                $avgVal = $facCount > 0 ? (float) $facGroup->avg($key) : 0;
                
                // Fallback to global avg if specific faculty has no entries yet
                if ($facCount == 0 && $totalRespondents > 0) {
                    $avgVal = (float) $allResponses->avg($key);
                }

                $tcrVal = round($avgVal * 25, 2);
                $rowTcr[$fac] = $tcrVal;
                $rowSum += $tcrVal;
                $sumFacultyTCR[$fac] += $tcrVal;
            }

            $countFac = count($facultyList);
            $rerataFakultasTcr = $countFac > 0 ? round($rowSum / $countFac, 2) : 0;

            $indicator = in_array($key, ['q1','q2','q3','q4','q5','q6','q7','q8']) ? 'Fasilitas Penyelenggara' : 'Sarana dan Prasarana';

            $matrixFakultas[] = [
                'key' => $key,
                'indikator' => $indicator,
                'pertanyaan' => $questionText,
                'tcr_per_fac' => $rowTcr,
                'rerata_fakultas' => $rerataFakultasTcr,
            ];
        }

        // Summary footer per faculty
        $avgTcrPerFaculty = [];
        $kategoriPerFaculty = [];
        $sumOverallRerata = 0;

        foreach ($facultyList as $fac) {
            $facAvg = count($questions) > 0 ? round($sumFacultyTCR[$fac] / count($questions), 2) : 0;
            $avgTcrPerFaculty[$fac] = $facAvg;
            $sumOverallRerata += $facAvg;

            if ($facAvg >= 88.31) {
                $kategoriPerFaculty[$fac] = 'Sangat Baik';
            } elseif ($facAvg >= 76.61) {
                $kategoriPerFaculty[$fac] = 'Baik';
            } elseif ($facAvg >= 65.00) {
                $kategoriPerFaculty[$fac] = 'Kurang Baik';
            } else {
                $kategoriPerFaculty[$fac] = 'Tidak Baik';
            }
        }

        $overallRerataTCR = count($facultyList) > 0 ? round($sumOverallRerata / count($facultyList), 2) : 0;

        if ($overallRerataTCR >= 88.31) {
            $overallRerataKategori = 'Sangat Baik';
        } elseif ($overallRerataTCR >= 76.61) {
            $overallRerataKategori = 'Baik';
        } elseif ($overallRerataTCR >= 65.00) {
            $overallRerataKategori = 'Kurang Baik';
        } else {
            $overallRerataKategori = 'Tidak Baik';
        }

        // 3. Prepare Chart Data for ApexCharts (Image 2 & Image 3)
        $chartCategories = [];
        $chartValues = [];

        foreach ($matrixFakultas as $item) {
            $shortText = mb_strimwidth($item['pertanyaan'], 0, 25, '...');
            $chartCategories[] = $shortText;
            $chartValues[] = $item['rerata_fakultas'];
        }

        return $dataTable->render('pages.evaluasipengenalanwawasanibnusina.index', compact(
            'rekapData',
            'totalRespondents',
            'overallAvgNI',
            'overallNIK',
            'overallMutu',
            'overallKategori',
            'overallBadge',
            'facultyList',
            'facultyShortNames',
            'matrixFakultas',
            'avgTcrPerFaculty',
            'kategoriPerFaculty',
            'overallRerataTCR',
            'overallRerataKategori',
            'chartCategories',
            'chartValues'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPengenalanWawasanIbnuSina::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasipengenalanwawasanibnusina.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiPengenalanWawasanIbnuSina::questions();
        return view('pages.evaluasipengenalanwawasanibnusina.create', compact('questions'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiPengenalanWawasanIbnuSinaRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiPengenalanWawasanIbnuSina::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi penyampaian materi berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasipengenalanwawasanibnusina.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiPengenalanWawasanIbnuSina::create($data);

        Alert::success('Berhasil', 'Evaluasi penyampaian materi berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipengenalanwawasanibnusina.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiPengenalanWawasanIbnuSina::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPengenalanWawasanIbnuSina::questions();
        return view('pages.evaluasipengenalanwawasanibnusina.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiPengenalanWawasanIbnuSina::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPengenalanWawasanIbnuSina::questions();
        return view('pages.evaluasipengenalanwawasanibnusina.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiPengenalanWawasanIbnuSinaRequest $request, $id)
    {
        $evaluasi = EvaluasiPengenalanWawasanIbnuSina::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi penyampaian materi berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipengenalanwawasanibnusina.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiPengenalanWawasanIbnuSina::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi penyampaian materi berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasipengenalanwawasanibnusina.index');
    }
}
