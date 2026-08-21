<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelompok;
use App\Models\AbsenPertama;
use App\Models\AbsenKedua;
use App\Models\AbsenKetiga;
use App\Models\HasilTest;
use App\Models\SoalTugasKelompok;
use App\Models\KedisiplinanPertama;
use App\Models\KedisiplinanKedua;
use App\Models\KedisiplinanKetiga;
use App\Models\SertifikatSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();
        if ($authUser->role === 'panitia') {
            return redirect()->route('lpj.index');
        }
        $isPendamping = in_array($authUser->role, ['kakakpendamping', 'dosenpendamping']);

        // Auto-assign sequential certificate numbers for any students with kelulusan_is_active whose certificate has not been numbered yet
        $lulusWithoutNomor = User::where('role', 'mahasiswa')
            ->where('kelulusan_is_active', true)
            ->whereNull('nomor_sertifikat')
            ->select('id', 'nomor_sertifikat', 'sertifikat_issued_at')
            ->orderBy('id')
            ->limit(10)
            ->get();

        if ($lulusWithoutNomor->isNotEmpty()) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($lulusWithoutNomor) {
                $setting = SertifikatSetting::firstOrCreate(['id' => 1]);
                $lockedSetting = SertifikatSetting::where('id', 1)->lockForUpdate()->first();
                $lastNum = $lockedSetting->nomor_urut_terakhir ?? 0;
                foreach ($lulusWithoutNomor as $lu) {
                    $lastNum++;
                    $lu->update([
                        'nomor_sertifikat' => $lastNum,
                        'sertifikat_issued_at' => now(),
                    ]);
                }
                $lockedSetting->update(['nomor_urut_terakhir' => $lastNum]);
            });
        }

        if ($isPendamping) {
            if ($authUser->role === 'kakakpendamping') {
                $myKelompokIds = Kelompok::where('pendamping_id', $authUser->id)
                    ->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))
                    ->pluck('id');
            } else { // dosenpendamping
                $myKelompokIds = Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', $authUser->id))
                    ->pluck('id');
            }

            $myKelompokList = Kelompok::whereIn('id', $myKelompokIds)->get();
            $myKelompokNama = $myKelompokList->pluck('nama_kelompok')->implode(', ');
            $myKelompokSlug = $myKelompokList->first()?->slug;
            $targetUserIds = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->pluck('id');
            $totalMahasiswa = $targetUserIds->count();

            // Attendance Stats
            $absen1 = AbsenPertama::whereIn('user_id', $targetUserIds)->where(function ($q) {
                $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen');
            })->count();
            $absen2 = AbsenKedua::whereIn('user_id', $targetUserIds)->where(function ($q) {
                $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen');
            })->count();
            $absen3 = AbsenKetiga::whereIn('user_id', $targetUserIds)->where(function ($q) {
                $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen');
            })->count();

            // 6 Pillars Penuntasan Akademik Counts for Kelompok
            $absenCount = User::whereIn('id', $targetUserIds)
                ->where(function ($q) {
                    $q->whereHas('absenPertama', fn($sub) => $sub->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                      ->orWhereHas('absenKedua', fn($sub) => $sub->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                      ->orWhereHas('absenKetiga', fn($sub) => $sub->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'));
                })->count();

            $disiplinCount = User::whereIn('id', $targetUserIds)
                ->where(function ($q) {
                    $q->whereHas('kedisiplinanPertama')
                      ->orWhereHas('kedisiplinanKedua')
                      ->orWhereHas('kedisiplinanKetiga');
                })->count();

            $pretestCount = HasilTest::where('type', 'pretest')->whereIn('user_id', $targetUserIds)->distinct('user_id')->count('user_id');
            $posttestCount = HasilTest::where('type', 'posttest')->whereIn('user_id', $targetUserIds)->distinct('user_id')->count('user_id');
            $tugasCount = SoalTugasKelompok::whereIn('user_id', $targetUserIds)->distinct('user_id')->count('user_id');
            $hasilTestTuntasCount = HasilTest::where('skor', '>=', 65)->whereIn('user_id', $targetUserIds)->distinct('user_id')->count('user_id');

            // Complete Snapshots for Kelompok Members
            $recentUsers = User::whereIn('id', $targetUserIds)->latest()->take(5)->get();

            $allAbsen1 = AbsenPertama::whereIn('user_id', $targetUserIds)->with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();
            $allAbsen2 = AbsenKedua::whereIn('user_id', $targetUserIds)->with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();
            $allAbsen3 = AbsenKetiga::whereIn('user_id', $targetUserIds)->with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();

            $allDis1 = KedisiplinanPertama::whereIn('user_id', $targetUserIds)->with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();
            $allDis2 = KedisiplinanKedua::whereIn('user_id', $targetUserIds)->with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();
            $allDis3 = KedisiplinanKetiga::whereIn('user_id', $targetUserIds)->with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();

            $allPretest = HasilTest::whereIn('user_id', $targetUserIds)->with('user:id,name,kelompok_id')->where('type', 'pretest')->latest()->take(10)->get();
            $allPosttest = HasilTest::whereIn('user_id', $targetUserIds)->with('user:id,name,kelompok_id')->where('type', 'posttest')->latest()->take(10)->get();
            $allTugas = SoalTugasKelompok::whereIn('user_id', $targetUserIds)->with('user:id,name,kelompok_id')->latest()->take(10)->get();

            $allM1 = User::whereIn('id', $targetUserIds)
                        ->whereHas('hasilTests', fn($q) => $q->where('modul', 1))
                        ->with(['hasilTests' => fn($q) => $q->where('modul', 1)->select('id', 'user_id', 'modul', 'type', 'skor')])
                        ->select('id', 'name', 'kelompok_id')
                        ->take(10)
                        ->get();
            $allM2 = User::whereIn('id', $targetUserIds)
                        ->whereHas('hasilTests', fn($q) => $q->where('modul', 2))
                        ->with(['hasilTests' => fn($q) => $q->where('modul', 2)->select('id', 'user_id', 'modul', 'type', 'skor')])
                        ->select('id', 'name', 'kelompok_id')
                        ->take(10)
                        ->get();
            $allM3 = User::whereIn('id', $targetUserIds)
                        ->whereHas('hasilTests', fn($q) => $q->where('modul', 3))
                        ->with(['hasilTests' => fn($q) => $q->where('modul', 3)->select('id', 'user_id', 'modul', 'type', 'skor')])
                        ->select('id', 'name', 'kelompok_id')
                        ->take(10)
                        ->get();
            $allM4 = User::whereIn('id', $targetUserIds)
                        ->whereHas('hasilTests', fn($q) => $q->where('modul', 4))
                        ->with(['hasilTests' => fn($q) => $q->where('modul', 4)->select('id', 'user_id', 'modul', 'type', 'skor')])
                        ->select('id', 'name', 'kelompok_id')
                        ->take(10)
                        ->get();
            $sertifikatSetting = SertifikatSetting::current();
            $sertifikatCount = User::whereIn('id', $targetUserIds)->whereNotNull('nomor_sertifikat')->count();
            $allSertifikatMahasiswa = User::where('role', 'mahasiswa')->whereIn('id', $targetUserIds)->with('kelompok:id,nama_kelompok')->select('id', 'name', 'id_pendaftar', 'program_studi', 'kelompok_id', 'nomor_sertifikat', 'kelulusan_is_active')->orderBy('name')->take(10)->get();
        } else {
            $myKelompokNama = null;
            $myKelompokSlug = null;
            // Global Counts
            $totalMahasiswa = User::where('role', 'mahasiswa')->count();
            $sertifikatSetting = SertifikatSetting::current();
            $sertifikatCount = User::where('role', 'mahasiswa')->whereNotNull('nomor_sertifikat')->count();
            $allSertifikatMahasiswa = User::where('role', 'mahasiswa')->with('kelompok:id,nama_kelompok')->select('id', 'name', 'id_pendaftar', 'program_studi', 'kelompok_id', 'nomor_sertifikat', 'kelulusan_is_active')->orderBy('name')->take(10)->get();

            // Attendance Stats (Hadir if either pagi or sore is filled)
            $absen1 = AbsenPertama::where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen')->count();
            $absen2 = AbsenKedua::where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen')->count();
            $absen3 = AbsenKetiga::where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen')->count();

            // 6 Pillars Penuntasan Akademik Counts
            $absenCount = User::where('role', 'mahasiswa')
                ->where(function ($q) {
                    $q->whereHas('absenPertama', fn($sub) => $sub->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                      ->orWhereHas('absenKedua', fn($sub) => $sub->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                      ->orWhereHas('absenKetiga', fn($sub) => $sub->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'));
                })->count();

            $disiplinCount = User::where('role', 'mahasiswa')
                ->where(function ($q) {
                    $q->whereHas('kedisiplinanPertama')
                      ->orWhereHas('kedisiplinanKedua')
                      ->orWhereHas('kedisiplinanKetiga');
                })->count();

            $pretestCount = HasilTest::where('type', 'pretest')->distinct('user_id')->count('user_id');
            $posttestCount = HasilTest::where('type', 'posttest')->distinct('user_id')->count('user_id');
            $tugasCount = SoalTugasKelompok::distinct('user_id')->count('user_id');
            $hasilTestTuntasCount = HasilTest::where('skor', '>=', 65)->distinct('user_id')->count('user_id');

            // Complete Snapshots for Dashboard Tables
            $recentUsers = User::latest()->take(5)->get();

            // Fetch snapshots with limit for fast loading
            $allAbsen1 = AbsenPertama::with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();
            $allAbsen2 = AbsenKedua::with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();
            $allAbsen3 = AbsenKetiga::with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();

            // Fetch Discipline Snapshots
            $allDis1 = KedisiplinanPertama::with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();
            $allDis2 = KedisiplinanKedua::with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();
            $allDis3 = KedisiplinanKetiga::with('user:id,name,kelompok_id')->latest('updated_at')->take(10)->get();

            $allPretest = HasilTest::with('user:id,name,kelompok_id')->where('type', 'pretest')->latest()->take(10)->get();
            $allPosttest = HasilTest::with('user:id,name,kelompok_id')->where('type', 'posttest')->latest()->take(10)->get();
            $allTugas = SoalTugasKelompok::with('user:id,name,kelompok_id')->latest()->take(10)->get();

            // Specific Module Snapshots
            $allM1 = User::where('role', 'mahasiswa')
                        ->whereHas('hasilTests', fn($q) => $q->where('modul', 1))
                        ->with(['hasilTests' => fn($q) => $q->where('modul', 1)->select('id', 'user_id', 'modul', 'type', 'skor')])
                        ->select('id', 'name', 'kelompok_id')
                        ->take(10)
                        ->get();
            $allM2 = User::where('role', 'mahasiswa')
                        ->whereHas('hasilTests', fn($q) => $q->where('modul', 2))
                        ->with(['hasilTests' => fn($q) => $q->where('modul', 2)->select('id', 'user_id', 'modul', 'type', 'skor')])
                        ->select('id', 'name', 'kelompok_id')
                        ->take(10)
                        ->get();
            $allM3 = User::where('role', 'mahasiswa')
                        ->whereHas('hasilTests', fn($q) => $q->where('modul', 3))
                        ->with(['hasilTests' => fn($q) => $q->where('modul', 3)->select('id', 'user_id', 'modul', 'type', 'skor')])
                        ->select('id', 'name', 'kelompok_id')
                        ->take(10)
                        ->get();
            $allM4 = User::where('role', 'mahasiswa')
                        ->whereHas('hasilTests', fn($q) => $q->where('modul', 4))
                        ->with(['hasilTests' => fn($q) => $q->where('modul', 4)->select('id', 'user_id', 'modul', 'type', 'skor')])
                        ->select('id', 'name', 'kelompok_id')
                        ->take(10)
                        ->get();
        }

        return view('dashboard.index', compact(
            'totalMahasiswa',
            'myKelompokNama',
            'myKelompokSlug',
            'absen1',
            'absen2',
            'absen3',
            'absenCount',
            'disiplinCount',
            'pretestCount',
            'posttestCount',
            'tugasCount',
            'hasilTestTuntasCount',
            'recentUsers',
            'allAbsen1',
            'allAbsen2',
            'allAbsen3',
            'allDis1',
            'allDis2',
            'allDis3',
            'allPretest',
            'allPosttest',
            'allTugas',
            'allM1',
            'allM2',
            'allM3',
            'allM4',
            'allSertifikatMahasiswa',
            'sertifikatSetting',
            'sertifikatCount'
        ));
    }
}