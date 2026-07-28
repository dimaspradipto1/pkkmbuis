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
        if ($user->role !== 'mahasiswa' || !$user->nomor_sertifikat) {
            abort(404, 'Sertifikat tidak ditemukan.');
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
            if ($di && !empty($di->kelengkapan_atribut) && !empty($di->ketepatan_waktu) && !empty($di->perilaku)) {
                $disDayCount++;
            }
        }
        $disComplete = $disDayCount >= 3;

        $pretestComplete = HasilTest::where('user_id', $user->id)->where('type', 'pretest')->distinct('modul')->count('modul') >= 4;
        $posttestComplete = HasilTest::where('user_id', $user->id)->where('type', 'posttest')->distinct('modul')->count('modul') >= 4;
        $tugasComplete = SoalTugasKelompok::where('user_id', $user->id)->exists();

        $evaluasiMap = [
            1 => \App\Models\EvaluasiPengenalanWawasanIbnuSina::class,
            2 => \App\Models\EvaluasiPelayananKemahasiswaanPusatPrestasi::class,
            3 => \App\Models\EvaluasiPelayanansistemAkademik::class,
            4 => \App\Models\EvaluasiPelayanansistemAdministrasiKeuangan::class,
            5 => \App\Models\EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::class,
            6 => \App\Models\EvaluasiSistemPendidikanTinggidiIndonesia::class,
            7 => \App\Models\EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::class,
            8 => \App\Models\EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::class,
            9 => \App\Models\Perpustakaan::class,
            10 => \App\Models\EvaluasiIkaUis::class,
            11 => \App\Models\EvaluasiKewirausahaan::class,
            12 => \App\Models\EvaluasiPencarianBakatMahasiswa::class,
            13 => \App\Models\EvaluasiMotivasiWaliKotaBatam::class,
            14 => \App\Models\EvaluasiMotivasiGubernurKepulauanRiau::class,
            15 => \App\Models\EvaluasiFikes::class,
            16 => \App\Models\EvaluasiFst::class,
            17 => \App\Models\EvaluasiFeb::class,
        ];

        $activeEvaluasiMenus = EvaluasiMenu::where('is_active', true)->get();
        $requiredEvaluasiTotal = $activeEvaluasiMenus->count();
        $completedEvaluasiTotal = 0;
        foreach ($activeEvaluasiMenus as $menu) {
            if (isset($evaluasiMap[$menu->nomor]) && $evaluasiMap[$menu->nomor]::where('user_id', $user->id)->exists()) {
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
                if (strtolower($di->perilaku ?? '') === 'sangat baik') $disPoints++;
            }
        }
        $scoreDisRaw = ($disPoints / 9) * 100;

        $finalScore = $scoreTestsRaw * 0.2 + $scoreAbsRaw * 0.5 + $scoreDisRaw * 0.3;
        $isPassed = $isAllComplete && $finalScore >= 65;

        return view('pages.sertifikat-verifikasi.show', [
            'user' => $user,
            'setting' => $setting,
            'isPassed' => $isPassed,
            'finalScore' => $finalScore,
            'isAllComplete' => $isAllComplete,
        ]);
    }
}
