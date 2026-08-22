<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiMenu;
use App\Models\HasilTest;
use App\Models\SertifikatSetting;
use App\Models\SoalTugasKelompok;
use App\Models\User;

class SertifikatVerifikasiController extends Controller
{
    /**
     * Public, signed-URL-only certificate verification page — scanned via the
     * QR code printed on the certificate itself. Mirrors the campus's existing
     * SIBAAK document-verification page pattern (signer info block + embedded
     * document), but for a PKKMB certificate instead of a surat.
     */
    public function show(User $user)
    {
        if ($user->role !== 'mahasiswa') {
            abort(404, 'Sertifikat tidak ditemukan.');
        }

        if (!$user->nomor_sertifikat && $user->kelulusan_is_active) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
                $setting = SertifikatSetting::firstOrCreate(['id' => 1]);
                $lockedSetting = SertifikatSetting::where('id', 1)->lockForUpdate()->first();
                $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();
                if ($lockedUser && !$lockedUser->nomor_sertifikat) {
                    $nextNumber = ($lockedSetting->nomor_urut_terakhir ?? 0) + 1;
                    $lockedSetting->update(['nomor_urut_terakhir' => $nextNumber]);
                    $lockedUser->update([
                        'nomor_sertifikat' => $nextNumber,
                        'sertifikat_issued_at' => now(),
                    ]);
                }
            });
            $user->refresh();
        }

        if (!$user->nomor_sertifikat) {
            abort(404, 'Sertifikat belum diterbitkan.');
        }

        $setting = SertifikatSetting::current();

        $absSessionCount = 0;
        foreach ([$user->absenPertama, $user->absenKedua, $user->absenKetiga] as $ab) {
            if ($ab) {
                if (!empty($ab->hadir_pagi) && $ab->hadir_pagi !== 'Belum Absen') $absSessionCount++;
                if (!empty($ab->hadir_sore) && $ab->hadir_sore !== 'Belum Absen') $absSessionCount++;
            }
        }
        $absComplete = $absSessionCount >= 6;

        $disDayCount = 0;
        foreach ([$user->kedisiplinanPertama, $user->kedisiplinanKedua, $user->kedisiplinanKetiga] as $di) {
            if ($di && !empty($di->kelengkapan_atribut) && $di->kelengkapan_atribut !== '-'
                    && !empty($di->ketepatan_waktu) && $di->ketepatan_waktu !== '-'
                    && !empty($di->perilaku) && $di->perilaku !== '-') {
                $disDayCount++;
            }
        }
        $disComplete = $disDayCount >= 3;

        $activePreModules = \App\Models\PreTestSetting::getActiveModules();
        $totalActivePre = count($activePreModules);

        $activePostModules = \App\Models\PostTestSetting::getActiveModules();
        $totalActivePost = count($activePostModules);

        $isM5Active = \App\Models\ModulSetting::isActive(5);

        $preCount = HasilTest::where('user_id', $user->id)->where('type', 'pretest')->whereIn('modul', $activePreModules)->distinct('modul')->count('modul');
        $pretestComplete = ($totalActivePre === 0) || ($preCount >= $totalActivePre);

        $postCount = HasilTest::where('user_id', $user->id)->where('type', 'posttest')->whereIn('modul', $activePostModules)->distinct('modul')->count('modul');
        $posttestComplete = ($totalActivePost === 0) || ($postCount >= $totalActivePost);

        $tugasComplete = !$isM5Active || SoalTugasKelompok::where('user_id', $user->id)->exists();

        $activeEvaluasiMenus = EvaluasiMenu::available()->where('is_active', true)->get();
        $requiredEvaluasiTotal = $activeEvaluasiMenus->count();
        $completedEvaluasiTotal = 0;
        foreach ($activeEvaluasiMenus as $menu) {
            $modelClass = $menu->model_class;
            if ($modelClass && $modelClass::where('user_id', $user->id)->exists()) {
                $completedEvaluasiTotal++;
            }
        }
        $evaluasiComplete = $requiredEvaluasiTotal === 0 || $completedEvaluasiTotal >= $requiredEvaluasiTotal;

        $isAllComplete = $absComplete && $disComplete && $pretestComplete && $posttestComplete && $tugasComplete && $evaluasiComplete;

        $allTests = HasilTest::where('user_id', $user->id)->get();
        $scoreTestsRaw = ($allTests->sum('skor') + ($tugasComplete ? 100 : 0)) / 9;
        $scoreAbsRaw = ($absSessionCount / 6) * 100;

        $disPoints = 0;
        foreach ([$user->kedisiplinanPertama, $user->kedisiplinanKedua, $user->kedisiplinanKetiga] as $di) {
            if ($di) {
                if (strtolower($di->kelengkapan_atribut ?? '') === 'lengkap') $disPoints++;
                if (strtolower($di->ketepatan_waktu ?? '') === 'tepat waktu') $disPoints++;
                if (in_array(strtolower($di->perilaku ?? ''), ['baik', 'sangat baik'])) $disPoints++;
            }
        }
        $scoreDisRaw = ($disPoints / 9) * 100;

        $finalScore = $scoreTestsRaw * 0.2 + $scoreAbsRaw * 0.5 + $scoreDisRaw * 0.3;
        $isPassed = ($isAllComplete && $finalScore >= 65) || (bool) $user->kelulusan_is_active;

        return view('pages.sertifikat-verifikasi.show', [
            'user' => $user,
            'setting' => $setting,
            'isPassed' => $isPassed,
            'finalScore' => $finalScore,
            'isAllComplete' => $isAllComplete,
        ]);
    }
}
