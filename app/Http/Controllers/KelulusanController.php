<?php

namespace App\Http\Controllers;

use App\DataTables\KelulusanDataTable;
use App\Models\EvaluasiMenu;
use App\Models\ModulSetting;
use App\Models\PostTestSetting;
use App\Models\PreTestSetting;
use App\Models\SertifikatSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use RealRashid\SweetAlert\Facades\Alert;

class KelulusanController extends Controller
{
    public function index(KelulusanDataTable $dataTable)
    {
        if (!in_array(Auth::user()->role, ['admin', 'stafbaak', 'pimpinan', 'timevaluasi'])) {
            abort(403);
        }

        // Compute summary statistics for KPI cards & checklist widget
        $students = User::where('role', 'mahasiswa')
            ->with([
                'absenPertama',
                'absenKedua',
                'absenKetiga',
                'kedisiplinanPertama',
                'kedisiplinanKedua',
                'kedisiplinanKetiga',
                'hasilTests',
                'tugasKelompok',
                'kelompok'
            ])
            ->get();

        $totalMahasiswa = $students->count();

        $activePreModules = PreTestSetting::getActiveModules();
        $totalActivePre = count($activePreModules);

        $activePostModules = PostTestSetting::getActiveModules();
        $totalActivePost = count($activePostModules);

        $isM5Active = ModulSetting::isActive(5);

        $activeEvaluasiMenus = EvaluasiMenu::available()->where('is_active', true)->get();
        $requiredEvaluasiTotal = $activeEvaluasiMenus->filter(fn($m) => !$m->isFacultyMenu())->count() + ($activeEvaluasiMenus->contains(fn($m) => $m->isFacultyMenu()) ? 1 : 0);

        $completedUserIdSets = [];
        foreach ($activeEvaluasiMenus as $menu) {
            $modelClass = $menu->model_class;
            if ($modelClass) {
                $completedUserIdSets[$menu->id] = $modelClass::pluck('user_id')->flip();
            }
        }

        $totalLulus = 0;
        $totalTidakLulus = 0;
        $totalBelumLengkap = 0;
        $countCompleteAbsensi = 0;
        $countCompleteKedisiplinan = 0;
        $countCompletePretest = 0;
        $countCompletePosttest = 0;
        $countCompleteTugas = 0;
        $countCompleteEvaluasi = 0;
        $countSertifikatIssued = 0;

        foreach ($students as $student) {
            // 1. Absensi (6 Sesi)
            $absCount = 0;
            $absPoints = 0;
            foreach ([$student->absenPertama, $student->absenKedua, $student->absenKetiga] as $ab) {
                if ($ab) {
                    if (!empty($ab->hadir_pagi) && $ab->hadir_pagi !== 'Belum Absen') {
                        $absCount++;
                        if (str_contains(strtolower($ab->hadir_pagi), 'hadir') && !str_contains(strtolower($ab->hadir_pagi), 'tidak')) {
                            $absPoints++;
                        }
                    }
                    if (!empty($ab->hadir_sore) && $ab->hadir_sore !== 'Belum Absen') {
                        $absCount++;
                        if (str_contains(strtolower($ab->hadir_sore), 'hadir') && !str_contains(strtolower($ab->hadir_sore), 'tidak')) {
                            $absPoints++;
                        }
                    }
                }
            }
            $absComplete = ($absCount >= 6);
            if ($absComplete) $countCompleteAbsensi++;

            // 2. Kedisiplinan (3 Hari)
            $disPoints = 0;
            $disDayCount = 0;
            foreach ([$student->kedisiplinanPertama, $student->kedisiplinanKedua, $student->kedisiplinanKetiga] as $di) {
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
            $disComplete = ($disDayCount >= 3);
            if ($disComplete) $countCompleteKedisiplinan++;

            // 3. Pre-Test
            $pretestCount = $student->hasilTests->where('type', 'pretest')->whereIn('modul', $activePreModules)->pluck('modul')->unique()->count();
            $pretestComplete = ($totalActivePre === 0) || ($pretestCount >= $totalActivePre);
            if ($pretestComplete) $countCompletePretest++;

            // 4. Post-Test
            $posttestCount = $student->hasilTests->where('type', 'posttest')->whereIn('modul', $activePostModules)->pluck('modul')->unique()->count();
            $posttestComplete = ($totalActivePost === 0) || ($posttestCount >= $totalActivePost);
            if ($posttestComplete) $countCompletePosttest++;

            // 5. Tugas Kelompok
            $tugasComplete = !$isM5Active || (bool) $student->tugasKelompok;
            if ($tugasComplete) $countCompleteTugas++;

            // 6. Evaluasi Materi
            $studentRelevantMenus = $activeEvaluasiMenus->filter(fn($m) => $m->matchesUserFaculty($student));
            $studentRequiredTotal = $studentRelevantMenus->count();
            $completedEvaluasi = 0;
            foreach ($studentRelevantMenus as $menu) {
                if (isset($completedUserIdSets[$menu->id][$student->id])) {
                    $completedEvaluasi++;
                }
            }
            $evaluasiComplete = ($studentRequiredTotal === 0) || ($completedEvaluasi >= $studentRequiredTotal);
            if ($evaluasiComplete) $countCompleteEvaluasi++;

            $isAllComplete = $absComplete && $disComplete && $pretestComplete && $posttestComplete && $tugasComplete && $evaluasiComplete;

            $sumPreTests = $student->hasilTests->where('type', 'pretest')->whereIn('modul', $activePreModules)->sum('skor');
            $sumPostTests = $student->hasilTests->where('type', 'posttest')->whereIn('modul', $activePostModules)->sum('skor');
            $sumTests = $sumPreTests + $sumPostTests + (($isM5Active && $tugasComplete) ? 100 : 0);
            $totalTestDenominator = $totalActivePre + $totalActivePost + ($isM5Active ? 1 : 0);
            $scoreTestsRaw = $totalTestDenominator > 0 ? ($sumTests / $totalTestDenominator) : 0;
            $scoreAbsRaw = ($absCount / 6) * 100;
            $scoreDisRaw = ($disPoints / 9) * 100;

            $finalScore = $scoreTestsRaw * 0.2 + $scoreAbsRaw * 0.5 + $scoreDisRaw * 0.3;
            $isPassed = $isAllComplete && ($finalScore >= 65);

            if ($isAllComplete && $isPassed && $student->nomor_sertifikat) {
                $countSertifikatIssued++;
            }

            if (!$isAllComplete) {
                $totalBelumLengkap++;
            } else {
                if ($isPassed) {
                    $totalLulus++;
                } else {
                    $totalTidakLulus++;
                }
            }
        }

        $stats = [
            'totalMahasiswa'            => $totalMahasiswa,
            'totalLulus'                => $totalLulus,
            'totalTidakLulus'           => $totalTidakLulus,
            'totalBelumLengkap'         => $totalBelumLengkap,
            'passRate'                  => $totalMahasiswa > 0 ? round(($totalLulus / $totalMahasiswa) * 100, 1) : 0,
            'countCompleteAbsensi'      => $countCompleteAbsensi,
            'countCompleteKedisiplinan' => $countCompleteKedisiplinan,
            'countCompletePretest'      => $countCompletePretest,
            'countCompletePosttest'     => $countCompletePosttest,
            'countCompleteTugas'        => $countCompleteTugas,
            'countCompleteEvaluasi'     => $countCompleteEvaluasi,
            'totalActivePre'            => $totalActivePre,
            'totalActivePost'           => $totalActivePost,
            'isM5Active'                => $isM5Active,
            'requiredEvaluasiTotal'     => $requiredEvaluasiTotal,
            'countSertifikatIssued'     => $countSertifikatIssued,
        ];

        return $dataTable->render('pages.kelulusan.index', compact('stats'));
    }

    public function getSertifikatData($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'stafbaak', 'pimpinan', 'timevaluasi'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::where('role', 'mahasiswa')->findOrFail($id);
        $sertifikatSetting = SertifikatSetting::current();

        if (!$user->nomor_sertifikat) {
            DB::transaction(function () use ($user) {
                $lockedSetting = SertifikatSetting::where('id', 1)->lockForUpdate()->first();
                $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();
                if ($lockedUser && !$lockedUser->nomor_sertifikat) {
                    $nextNumber = $lockedSetting->nomor_urut_terakhir + 1;
                    $lockedSetting->update(['nomor_urut_terakhir' => $nextNumber]);
                    $lockedUser->update(['nomor_sertifikat' => $nextNumber, 'sertifikat_issued_at' => now()]);
                }
            });
            $user->refresh();
        }

        $verifikasiUrl = URL::signedRoute('sertifikat.verifikasi', ['user' => $user->id]);

        return response()->json([
            'success' => true,
            'nomorUrut' => str_pad($user->nomor_sertifikat, 4, '0', STR_PAD_LEFT),
            'kodeSurat' => $sertifikatSetting->kode_surat,
            'nomorSertifikatLengkap' => '#' . str_pad($user->nomor_sertifikat, 4, '0', STR_PAD_LEFT) . '/' . $sertifikatSetting->kode_surat,
            'namaMahasiswa' => $user->name,
            'npm' => $user->nim ?: '-',
            'prodi' => $user->program_studi ?? '-',
            'fakultas' => $user->fakultas ?? '-',
            'statusLulus' => true,
            'namaKegiatan' => $sertifikatSetting->nama_kegiatan,
            'lokasi' => $sertifikatSetting->lokasi,
            'tanggal' => $sertifikatSetting->tanggal_pelaksanaan,
            'namaMengetahui' => $sertifikatSetting->nama_mengetahui,
            'jabatanMengetahui' => $sertifikatSetting->jabatan_mengetahui,
            'nipMengetahui' => $sertifikatSetting->nip_mengetahui,
            'namaKetuaPanitia' => $sertifikatSetting->nama_ketua_panitia,
            'jabatanKetuaPanitia' => $sertifikatSetting->jabatan_ketua_panitia,
            'nupKetuaPanitia' => $sertifikatSetting->nup_ketua_panitia,
            'logoDikti' => $sertifikatSetting->logo_dikti ? asset('storage/' . $sertifikatSetting->logo_dikti) : null,
            'logoBelmawa' => $sertifikatSetting->logo_belmawa ? asset('storage/' . $sertifikatSetting->logo_belmawa) : null,
            'logoPkkmb' => $sertifikatSetting->logo_pkkmb ? asset('storage/' . $sertifikatSetting->logo_pkkmb) : asset('assets/img/logopkkmb.png'),
            'logoKampus' => $sertifikatSetting->logo_kampus ? asset('storage/' . $sertifikatSetting->logo_kampus) : asset('assets/img/logo_ibsi.png'),
            'logoLima' => $sertifikatSetting->logo_lima ? asset('storage/' . $sertifikatSetting->logo_lima) : null,
            'verifikasiUrl' => $verifikasiUrl,
        ]);
    }

    public function toggle($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
            abort(403);
        }

        $user = User::where('role', 'mahasiswa')->findOrFail($id);
        $user->kelulusan_is_active = !$user->kelulusan_is_active;
        $user->save();

        $status = $user->kelulusan_is_active ? 'ditampilkan' : 'disembunyikan';
        Alert::success('Berhasil', "Status kelulusan untuk '{$user->name}' berhasil {$status}.")->toToast()->autoClose(3000);

        return redirect()->back();
    }

    public function bulkToggle(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
            abort(403);
        }

        $action = $request->input('action');
        $status = ($action === 'enable_all');

        User::where('role', 'mahasiswa')->update(['kelulusan_is_active' => $status]);

        $statusText = $status ? 'ditampilkan (DIBUKA) untuk seluruh mahasiswa' : 'disembunyikan (DITUTUP) untuk seluruh mahasiswa';
        Alert::success('Berhasil', "Tampilan kelulusan & sertifikat berhasil {$statusText}.")->toToast()->autoClose(3000);

        return redirect()->back();
    }
}

