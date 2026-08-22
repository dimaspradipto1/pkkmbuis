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
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();
        if ($authUser->role === 'panitia') {
            return redirect()->route('lpj.index');
        }

        $sertifikatSetting = SertifikatSetting::current();

        // 1. Role: Mahasiswa -> Super light loading (only personal student progress)
        if ($authUser->role === 'mahasiswa') {
            return view('dashboard.index', [
                'totalMahasiswa' => 1,
                'myKelompokNama' => $authUser->kelompok?->nama_kelompok,
                'myKelompokSlug' => $authUser->kelompok?->slug,
                'absen1' => 0,
                'absen2' => 0,
                'absen3' => 0,
                'absenCount' => 0,
                'disiplinCount' => 0,
                'pretestCount' => 0,
                'posttestCount' => 0,
                'tugasCount' => 0,
                'hasilTestTuntasCount' => 0,
                'recentUsers' => collect(),
                'allAbsen1' => collect(),
                'allAbsen2' => collect(),
                'allAbsen3' => collect(),
                'allDis1' => collect(),
                'allDis2' => collect(),
                'allDis3' => collect(),
                'allPretest' => collect(),
                'allPosttest' => collect(),
                'allTugas' => collect(),
                'allM1' => collect(),
                'allM2' => collect(),
                'allM3' => collect(),
                'allM4' => collect(),
                'allSertifikatMahasiswa' => collect(),
                'sertifikatSetting' => $sertifikatSetting,
                'sertifikatCount' => 0
            ]);
        }

        // 2. Role: Kakak Pendamping / Dosen Pendamping -> Scoped to their Kelompok
        $isPendamping = in_array($authUser->role, ['kakakpendamping', 'dosenpendamping']);
        if ($isPendamping) {
            $cacheKey = "dashboard_pendamping_{$authUser->id}";
            $data = Cache::remember($cacheKey, 60, function () use ($authUser) {
                if ($authUser->role === 'kakakpendamping') {
                    $myKelompokIds = Kelompok::where('pendamping_id', $authUser->id)
                        ->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))
                        ->pluck('id');
                } else {
                    $myKelompokIds = Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', $authUser->id))
                        ->pluck('id');
                }

                $myKelompokList = Kelompok::whereIn('id', $myKelompokIds)->get();
                $myKelompokNama = $myKelompokList->pluck('nama_kelompok')->implode(', ');
                $myKelompokSlug = $myKelompokList->first()?->slug;

                $targetUserIds = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->pluck('id');
                $totalMahasiswa = $targetUserIds->count();

                if ($totalMahasiswa === 0) {
                    return [
                        'totalMahasiswa' => 0,
                        'myKelompokNama' => $myKelompokNama,
                        'myKelompokSlug' => $myKelompokSlug,
                        'absen1' => 0,
                        'absen2' => 0,
                        'absen3' => 0,
                        'absenCount' => 0,
                        'disiplinCount' => 0,
                        'pretestCount' => 0,
                        'posttestCount' => 0,
                        'tugasCount' => 0,
                        'hasilTestTuntasCount' => 0,
                        'recentUsers' => collect(),
                        'allAbsen1' => collect(),
                        'allAbsen2' => collect(),
                        'allAbsen3' => collect(),
                        'allDis1' => collect(),
                        'allDis2' => collect(),
                        'allDis3' => collect(),
                        'allPretest' => collect(),
                        'allPosttest' => collect(),
                        'allTugas' => collect(),
                        'allM1' => collect(),
                        'allM2' => collect(),
                        'allM3' => collect(),
                        'allM4' => collect(),
                        'allSertifikatMahasiswa' => collect(),
                        'sertifikatCount' => 0,
                    ];
                }

                // Attendance Daily Counts
                $absen1 = AbsenPertama::whereIn('user_id', $targetUserIds)
                    ->where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                    ->count();
                $absen2 = AbsenKedua::whereIn('user_id', $targetUserIds)
                    ->where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                    ->count();
                $absen3 = AbsenKetiga::whereIn('user_id', $targetUserIds)
                    ->where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                    ->count();

                // 6 Pillars Unique Counts
                $absenUserIds = collect()
                    ->merge(AbsenPertama::whereIn('user_id', $targetUserIds)->where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))->pluck('user_id'))
                    ->merge(AbsenKedua::whereIn('user_id', $targetUserIds)->where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))->pluck('user_id'))
                    ->merge(AbsenKetiga::whereIn('user_id', $targetUserIds)->where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))->pluck('user_id'))
                    ->unique();
                $absenCount = $absenUserIds->count();

                $disFilter = fn($q) => $q->where(fn($sub) => $sub->whereNotNull('kelengkapan_atribut')->where('kelengkapan_atribut', '!=', '')->where('kelengkapan_atribut', '!=', '-'))
                                         ->where(fn($sub) => $sub->whereNotNull('ketepatan_waktu')->where('ketepatan_waktu', '!=', '')->where('ketepatan_waktu', '!=', '-'))
                                         ->where(fn($sub) => $sub->whereNotNull('perilaku')->where('perilaku', '!=', '')->where('perilaku', '!=', '-'));

                $disiplinUserIds = collect()
                    ->merge(KedisiplinanPertama::whereIn('user_id', $targetUserIds)->where($disFilter)->pluck('user_id'))
                    ->merge(KedisiplinanKedua::whereIn('user_id', $targetUserIds)->where($disFilter)->pluck('user_id'))
                    ->merge(KedisiplinanKetiga::whereIn('user_id', $targetUserIds)->where($disFilter)->pluck('user_id'))
                    ->unique();
                $disiplinCount = $disiplinUserIds->count();

                $tugasCount = SoalTugasKelompok::whereIn('user_id', $targetUserIds)
                    ->where(fn($q) => $q->whereNotNull('link_tugas')->orWhereNotNull('file_tugas'))
                    ->distinct('user_id')
                    ->count('user_id');
                $hasilTestTuntasCount = HasilTest::where('skor', '>=', 65)->whereIn('user_id', $targetUserIds)->distinct('user_id')->count('user_id');
                $sertifikatCount = User::whereIn('id', $targetUserIds)->whereNotNull('nomor_sertifikat')->count();

                // Snapshots for Kelompok Members
                $recentUsers = User::whereIn('id', $targetUserIds)->latest()->take(5)->get();

                $allAbsen1 = AbsenPertama::whereIn('user_id', $targetUserIds)
                    ->where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                    ->with('user:id,name,kelompok_id')
                    ->latest('updated_at')
                    ->take(10)
                    ->get();
                $allAbsen2 = AbsenKedua::whereIn('user_id', $targetUserIds)
                    ->where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                    ->with('user:id,name,kelompok_id')
                    ->latest('updated_at')
                    ->take(10)
                    ->get();
                $allAbsen3 = AbsenKetiga::whereIn('user_id', $targetUserIds)
                    ->where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                    ->with('user:id,name,kelompok_id')
                    ->latest('updated_at')
                    ->take(10)
                    ->get();

                $allDis1 = KedisiplinanPertama::whereIn('user_id', $targetUserIds)
                    ->where($disFilter)
                    ->with('user:id,name,kelompok_id')
                    ->latest('updated_at')
                    ->take(10)
                    ->get();
                $allDis2 = KedisiplinanKedua::whereIn('user_id', $targetUserIds)
                    ->where($disFilter)
                    ->with('user:id,name,kelompok_id')
                    ->latest('updated_at')
                    ->take(10)
                    ->get();
                $allDis3 = KedisiplinanKetiga::whereIn('user_id', $targetUserIds)
                    ->where($disFilter)
                    ->with('user:id,name,kelompok_id')
                    ->latest('updated_at')
                    ->take(10)
                    ->get();

                $allPretest = HasilTest::whereIn('user_id', $targetUserIds)->with('user:id,name,kelompok_id')->where('type', 'pretest')->latest()->take(10)->get();
                $allPosttest = HasilTest::whereIn('user_id', $targetUserIds)->with('user:id,name,kelompok_id')->where('type', 'posttest')->latest()->take(10)->get();
                $allTugas = SoalTugasKelompok::whereIn('user_id', $targetUserIds)
                    ->where(fn($q) => $q->whereNotNull('link_tugas')->orWhereNotNull('file_tugas'))
                    ->with('user:id,name,kelompok_id')
                    ->latest('updated_at')
                    ->take(10)
                    ->get();

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

                $allSertifikatMahasiswa = User::where('role', 'mahasiswa')
                    ->whereIn('id', $targetUserIds)
                    ->where(fn($q) => $q->whereNotNull('nomor_sertifikat')->orWhere('kelulusan_is_active', true))
                    ->with('kelompok:id,nama_kelompok')
                    ->select('id', 'name', 'id_pendaftar', 'program_studi', 'kelompok_id', 'nomor_sertifikat', 'kelulusan_is_active')
                    ->orderBy('name')
                    ->take(10)
                    ->get();

                return compact(
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
                    'sertifikatCount'
                );
            });

            $data['sertifikatSetting'] = $sertifikatSetting;
            return view('dashboard.index', $data);
        }

        // 3. Role: Admin / Superadmin / Staf BAAK / etc. -> Global Data with 60s Cache
        $globalData = Cache::remember('dashboard_global_stats', 60, function () {
            $totalMahasiswa = User::where('role', 'mahasiswa')->count();
            $sertifikatCount = User::where('role', 'mahasiswa')->whereNotNull('nomor_sertifikat')->count();

            // Attendance Daily Counts
            $absen1 = AbsenPertama::where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))->count();
            $absen2 = AbsenKedua::where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))->count();
            $absen3 = AbsenKetiga::where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))->count();

            // 6 Pillars Unique Counts
            $absenUserIds = collect()
                ->merge(AbsenPertama::where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))->pluck('user_id'))
                ->merge(AbsenKedua::where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))->pluck('user_id'))
                ->merge(AbsenKetiga::where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))->pluck('user_id'))
                ->unique();
            $absenCount = $absenUserIds->count();

            $disFilter = fn($q) => $q->where(fn($sub) => $sub->whereNotNull('kelengkapan_atribut')->where('kelengkapan_atribut', '!=', '')->where('kelengkapan_atribut', '!=', '-'))
                                     ->where(fn($sub) => $sub->whereNotNull('ketepatan_waktu')->where('ketepatan_waktu', '!=', '')->where('ketepatan_waktu', '!=', '-'))
                                     ->where(fn($sub) => $sub->whereNotNull('perilaku')->where('perilaku', '!=', '')->where('perilaku', '!=', '-'));

            $disiplinUserIds = collect()
                ->merge(KedisiplinanPertama::where($disFilter)->pluck('user_id'))
                ->merge(KedisiplinanKedua::where($disFilter)->pluck('user_id'))
                ->merge(KedisiplinanKetiga::where($disFilter)->pluck('user_id'))
                ->unique();
            $disiplinCount = $disiplinUserIds->count();

            $pretestCount = HasilTest::where('type', 'pretest')->distinct('user_id')->count('user_id');
            $posttestCount = HasilTest::where('type', 'posttest')->distinct('user_id')->count('user_id');
            $tugasCount = SoalTugasKelompok::where(fn($q) => $q->whereNotNull('link_tugas')->orWhereNotNull('file_tugas'))->distinct('user_id')->count('user_id');
            $hasilTestTuntasCount = HasilTest::where('skor', '>=', 65)->distinct('user_id')->count('user_id');

            // Complete Snapshots
            $recentUsers = User::where('role', 'mahasiswa')->latest()->take(5)->get();

            $allAbsen1 = AbsenPertama::where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                ->with('user:id,name,kelompok_id')
                ->latest('updated_at')
                ->take(10)
                ->get();
            $allAbsen2 = AbsenKedua::where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                ->with('user:id,name,kelompok_id')
                ->latest('updated_at')
                ->take(10)
                ->get();
            $allAbsen3 = AbsenKetiga::where(fn($q) => $q->where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen'))
                ->with('user:id,name,kelompok_id')
                ->latest('updated_at')
                ->take(10)
                ->get();

            $allDis1 = KedisiplinanPertama::where($disFilter)
                ->with('user:id,name,kelompok_id')
                ->latest('updated_at')
                ->take(10)
                ->get();
            $allDis2 = KedisiplinanKedua::where($disFilter)
                ->with('user:id,name,kelompok_id')
                ->latest('updated_at')
                ->take(10)
                ->get();
            $allDis3 = KedisiplinanKetiga::where($disFilter)
                ->with('user:id,name,kelompok_id')
                ->latest('updated_at')
                ->take(10)
                ->get();

            $allPretest = HasilTest::with('user:id,name,kelompok_id')->where('type', 'pretest')->latest()->take(10)->get();
            $allPosttest = HasilTest::with('user:id,name,kelompok_id')->where('type', 'posttest')->latest()->take(10)->get();
            $allTugas = SoalTugasKelompok::where(fn($q) => $q->whereNotNull('link_tugas')->orWhereNotNull('file_tugas'))
                ->with('user:id,name,kelompok_id')
                ->latest('updated_at')
                ->take(10)
                ->get();

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

            $allSertifikatMahasiswa = User::where('role', 'mahasiswa')
                ->where(fn($q) => $q->whereNotNull('nomor_sertifikat')->orWhere('kelulusan_is_active', true))
                ->with('kelompok:id,nama_kelompok')
                ->select('id', 'name', 'id_pendaftar', 'program_studi', 'kelompok_id', 'nomor_sertifikat', 'kelulusan_is_active')
                ->orderBy('name')
                ->take(10)
                ->get();

            return compact(
                'totalMahasiswa',
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
                'sertifikatCount'
            );
        });

        $globalData['myKelompokNama'] = null;
        $globalData['myKelompokSlug'] = null;
        $globalData['sertifikatSetting'] = $sertifikatSetting;

        return view('dashboard.index', $globalData);
    }
}