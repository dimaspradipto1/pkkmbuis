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
                'absenDatang' => 0,
                'absenPulang' => 0,
                'absenDatang1' => 0,
                'absenPulang1' => 0,
                'absenDatang2' => 0,
                'absenPulang2' => 0,
                'absenDatang3' => 0,
                'absenPulang3' => 0,
                'totalPresensi' => 0,
                'absenCount' => 0,
                'dis1Count' => 0,
                'dis2Count' => 0,
                'dis3Count' => 0,
                'totalKedisiplinan' => 0,
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

                // Attendance Daily & Sesi Counts (Datang & Pulang)
                $absenDatang1 = AbsenPertama::whereIn('user_id', $targetUserIds)->whereNotNull('hadir_pagi')->where('hadir_pagi', '!=', 'Belum Absen')->count();
                $absenPulang1 = AbsenPertama::whereIn('user_id', $targetUserIds)->whereNotNull('hadir_sore')->where('hadir_sore', '!=', 'Belum Absen')->count();
                $absenDatang2 = AbsenKedua::whereIn('user_id', $targetUserIds)->whereNotNull('hadir_pagi')->where('hadir_pagi', '!=', 'Belum Absen')->count();
                $absenPulang2 = AbsenKedua::whereIn('user_id', $targetUserIds)->whereNotNull('hadir_sore')->where('hadir_sore', '!=', 'Belum Absen')->count();
                $absenDatang3 = AbsenKetiga::whereIn('user_id', $targetUserIds)->whereNotNull('hadir_pagi')->where('hadir_pagi', '!=', 'Belum Absen')->count();
                $absenPulang3 = AbsenKetiga::whereIn('user_id', $targetUserIds)->whereNotNull('hadir_sore')->where('hadir_sore', '!=', 'Belum Absen')->count();

                $absenDatang = $absenDatang1 + $absenDatang2 + $absenDatang3;
                $absenPulang = $absenPulang1 + $absenPulang2 + $absenPulang3;
                $totalPresensi = $absenDatang + $absenPulang;

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

                $dis1Count = KedisiplinanPertama::whereIn('user_id', $targetUserIds)->where($disFilter)->count();
                $dis2Count = KedisiplinanKedua::whereIn('user_id', $targetUserIds)->where($disFilter)->count();
                $dis3Count = KedisiplinanKetiga::whereIn('user_id', $targetUserIds)->where($disFilter)->count();
                $totalKedisiplinan = $dis1Count + $dis2Count + $dis3Count;

                $disiplinUserIds = collect()
                    ->merge(KedisiplinanPertama::whereIn('user_id', $targetUserIds)->where($disFilter)->pluck('user_id'))
                    ->merge(KedisiplinanKedua::whereIn('user_id', $targetUserIds)->where($disFilter)->pluck('user_id'))
                    ->merge(KedisiplinanKetiga::whereIn('user_id', $targetUserIds)->where($disFilter)->pluck('user_id'))
                    ->unique();
                $disiplinCount = $disiplinUserIds->count();

                $pretestCount = HasilTest::where('type', 'pretest')->whereIn('user_id', $targetUserIds)->distinct('user_id')->count('user_id');
                $posttestCount = HasilTest::where('type', 'posttest')->whereIn('user_id', $targetUserIds)->distinct('user_id')->count('user_id');
                $tugasCount = SoalTugasKelompok::whereIn('user_id', $targetUserIds)
                    ->whereNotNull('link_tugas')
                    ->where('link_tugas', '!=', '')
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
                    ->whereNotNull('link_tugas')
                    ->where('link_tugas', '!=', '')
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
                    ->select('id', 'name', 'id_pendaftar', 'nim', 'program_studi', 'kelompok_id', 'nomor_sertifikat', 'kelulusan_is_active')
                    ->orderBy('name')
                    ->take(10)
                    ->get();

                $chartsData = $this->getDashboardChartsData($targetUserIds);

                return array_merge(compact(
                    'totalMahasiswa',
                    'myKelompokNama',
                    'myKelompokSlug',
                    'absen1',
                    'absen2',
                    'absen3',
                    'absenDatang',
                    'absenPulang',
                    'absenDatang1',
                    'absenPulang1',
                    'absenDatang2',
                    'absenPulang2',
                    'absenDatang3',
                    'absenPulang3',
                    'totalPresensi',
                    'absenCount',
                    'dis1Count',
                    'dis2Count',
                    'dis3Count',
                    'totalKedisiplinan',
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
                ), $chartsData);
            });

            $data['sertifikatSetting'] = $sertifikatSetting;
            return view('dashboard.index', $data);
        }

        // 3. Role: Admin / Superadmin / Staf BAAK / etc. -> Global Data with 60s Cache
        $globalData = Cache::remember('dashboard_global_stats', 60, function () {
            $totalMahasiswa = User::where('role', 'mahasiswa')->count();
            $sertifikatCount = User::where('role', 'mahasiswa')->whereNotNull('nomor_sertifikat')->count();

            // Attendance Daily & Sesi Counts (Datang & Pulang)
            $absenDatang1 = AbsenPertama::whereNotNull('hadir_pagi')->where('hadir_pagi', '!=', 'Belum Absen')->count();
            $absenPulang1 = AbsenPertama::whereNotNull('hadir_sore')->where('hadir_sore', '!=', 'Belum Absen')->count();
            $absenDatang2 = AbsenKedua::whereNotNull('hadir_pagi')->where('hadir_pagi', '!=', 'Belum Absen')->count();
            $absenPulang2 = AbsenKedua::whereNotNull('hadir_sore')->where('hadir_sore', '!=', 'Belum Absen')->count();
            $absenDatang3 = AbsenKetiga::whereNotNull('hadir_pagi')->where('hadir_pagi', '!=', 'Belum Absen')->count();
            $absenPulang3 = AbsenKetiga::whereNotNull('hadir_sore')->where('hadir_sore', '!=', 'Belum Absen')->count();

            $absenDatang = $absenDatang1 + $absenDatang2 + $absenDatang3;
            $absenPulang = $absenPulang1 + $absenPulang2 + $absenPulang3;
            $totalPresensi = $absenDatang + $absenPulang;

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

            $dis1Count = KedisiplinanPertama::where($disFilter)->count();
            $dis2Count = KedisiplinanKedua::where($disFilter)->count();
            $dis3Count = KedisiplinanKetiga::where($disFilter)->count();
            $totalKedisiplinan = $dis1Count + $dis2Count + $dis3Count;

            $disiplinUserIds = collect()
                ->merge(KedisiplinanPertama::where($disFilter)->pluck('user_id'))
                ->merge(KedisiplinanKedua::where($disFilter)->pluck('user_id'))
                ->merge(KedisiplinanKetiga::where($disFilter)->pluck('user_id'))
                ->unique();
            $disiplinCount = $disiplinUserIds->count();

            $pretestCount = HasilTest::where('type', 'pretest')->distinct('user_id')->count('user_id');
            $posttestCount = HasilTest::where('type', 'posttest')->distinct('user_id')->count('user_id');
            $tugasCount = SoalTugasKelompok::whereNotNull('link_tugas')->where('link_tugas', '!=', '')->distinct('user_id')->count('user_id');
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
            $allTugas = SoalTugasKelompok::whereNotNull('link_tugas')->where('link_tugas', '!=', '')
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
                ->select('id', 'name', 'id_pendaftar', 'nim', 'program_studi', 'kelompok_id', 'nomor_sertifikat', 'kelulusan_is_active')
                ->orderBy('name')
                ->take(10)
                ->get();

            $chartsData = $this->getDashboardChartsData(null);

            return array_merge(compact(
                'totalMahasiswa',
                'absen1',
                'absen2',
                'absen3',
                'absenDatang',
                'absenPulang',
                'absenDatang1',
                'absenPulang1',
                'absenDatang2',
                'absenPulang2',
                'absenDatang3',
                'absenPulang3',
                'totalPresensi',
                'absenCount',
                'dis1Count',
                'dis2Count',
                'dis3Count',
                'totalKedisiplinan',
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
            ), $chartsData);
        });

        $globalData['myKelompokNama'] = null;
        $globalData['myKelompokSlug'] = null;
        $globalData['sertifikatSetting'] = $sertifikatSetting;

        return view('dashboard.index', $globalData);
    }

    /**
     * Helper to compute chart series data for Absensi, Kedisiplinan, and Pre/Post Test
     */
    private function getDashboardChartsData($targetUserIds = null)
    {
        $userIdFilter = function ($query) use ($targetUserIds) {
            if ($targetUserIds !== null) {
                $query->whereIn('user_id', $targetUserIds);
            }
        };

        // 1. Absensi (Hari 1, Hari 2, Hari 3 - Datang vs Pulang & Status Breakdown)
        $h1Pagi = AbsenPertama::where($userIdFilter)->selectRaw('hadir_pagi, count(*) as total')->groupBy('hadir_pagi')->pluck('total', 'hadir_pagi')->toArray();
        $h1Sore = AbsenPertama::where($userIdFilter)->selectRaw('hadir_sore, count(*) as total')->groupBy('hadir_sore')->pluck('total', 'hadir_sore')->toArray();
        $h2Pagi = AbsenKedua::where($userIdFilter)->selectRaw('hadir_pagi, count(*) as total')->groupBy('hadir_pagi')->pluck('total', 'hadir_pagi')->toArray();
        $h2Sore = AbsenKedua::where($userIdFilter)->selectRaw('hadir_sore, count(*) as total')->groupBy('hadir_sore')->pluck('total', 'hadir_sore')->toArray();
        $h3Pagi = AbsenKetiga::where($userIdFilter)->selectRaw('hadir_pagi, count(*) as total')->groupBy('hadir_pagi')->pluck('total', 'hadir_pagi')->toArray();
        $h3Sore = AbsenKetiga::where($userIdFilter)->selectRaw('hadir_sore, count(*) as total')->groupBy('hadir_sore')->pluck('total', 'hadir_sore')->toArray();

        $chartAbsensi = [
            'categories' => ['Hari 1 (H-1)', 'Hari 2 (H-2)', 'Hari 3 (H-3)'],
            'datang' => [
                'hadir' => [($h1Pagi['Hadir'] ?? 0), ($h2Pagi['Hadir'] ?? 0), ($h3Pagi['Hadir'] ?? 0)],
                'izin' => [($h1Pagi['Izin'] ?? 0), ($h2Pagi['Izin'] ?? 0), ($h3Pagi['Izin'] ?? 0)],
                'sakit' => [($h1Pagi['Sakit'] ?? 0), ($h2Pagi['Sakit'] ?? 0), ($h3Pagi['Sakit'] ?? 0)],
                'alpa' => [($h1Pagi['Alpa'] ?? 0), ($h2Pagi['Alpa'] ?? 0), ($h3Pagi['Alpa'] ?? 0)],
            ],
            'pulang' => [
                'hadir' => [($h1Sore['Hadir'] ?? 0), ($h2Sore['Hadir'] ?? 0), ($h3Sore['Hadir'] ?? 0)],
                'izin' => [($h1Sore['Izin'] ?? 0), ($h2Sore['Izin'] ?? 0), ($h3Sore['Izin'] ?? 0)],
                'sakit' => [($h1Sore['Sakit'] ?? 0), ($h2Sore['Sakit'] ?? 0), ($h3Sore['Sakit'] ?? 0)],
                'alpa' => [($h1Sore['Alpa'] ?? 0), ($h2Sore['Alpa'] ?? 0), ($h3Sore['Alpa'] ?? 0)],
            ],
            'totalDatang' => [
                (($h1Pagi['Hadir'] ?? 0) + ($h1Pagi['Izin'] ?? 0) + ($h1Pagi['Sakit'] ?? 0) + ($h1Pagi['Alpa'] ?? 0)),
                (($h2Pagi['Hadir'] ?? 0) + ($h2Pagi['Izin'] ?? 0) + ($h2Pagi['Sakit'] ?? 0) + ($h2Pagi['Alpa'] ?? 0)),
                (($h3Pagi['Hadir'] ?? 0) + ($h3Pagi['Izin'] ?? 0) + ($h3Pagi['Sakit'] ?? 0) + ($h3Pagi['Alpa'] ?? 0)),
            ],
            'totalPulang' => [
                (($h1Sore['Hadir'] ?? 0) + ($h1Sore['Izin'] ?? 0) + ($h1Sore['Sakit'] ?? 0) + ($h1Sore['Alpa'] ?? 0)),
                (($h2Sore['Hadir'] ?? 0) + ($h2Sore['Izin'] ?? 0) + ($h2Sore['Sakit'] ?? 0) + ($h2Sore['Alpa'] ?? 0)),
                (($h3Sore['Hadir'] ?? 0) + ($h3Sore['Izin'] ?? 0) + ($h3Sore['Sakit'] ?? 0) + ($h3Sore['Alpa'] ?? 0)),
            ],
        ];

        // 2. Kedisiplinan
        $dis1Atribut = KedisiplinanPertama::where($userIdFilter)->selectRaw('LOWER(kelengkapan_atribut) as val, count(*) as total')->groupBy('val')->pluck('total', 'val')->toArray();
        $dis2Atribut = KedisiplinanKedua::where($userIdFilter)->selectRaw('LOWER(kelengkapan_atribut) as val, count(*) as total')->groupBy('val')->pluck('total', 'val')->toArray();
        $dis3Atribut = KedisiplinanKetiga::where($userIdFilter)->selectRaw('LOWER(kelengkapan_atribut) as val, count(*) as total')->groupBy('val')->pluck('total', 'val')->toArray();

        $dis1Waktu = KedisiplinanPertama::where($userIdFilter)->selectRaw('LOWER(ketepatan_waktu) as val, count(*) as total')->groupBy('val')->pluck('total', 'val')->toArray();
        $dis2Waktu = KedisiplinanKedua::where($userIdFilter)->selectRaw('LOWER(ketepatan_waktu) as val, count(*) as total')->groupBy('val')->pluck('total', 'val')->toArray();
        $dis3Waktu = KedisiplinanKetiga::where($userIdFilter)->selectRaw('LOWER(ketepatan_waktu) as val, count(*) as total')->groupBy('val')->pluck('total', 'val')->toArray();

        $dis1Perilaku = KedisiplinanPertama::where($userIdFilter)->selectRaw('LOWER(perilaku) as val, count(*) as total')->groupBy('val')->pluck('total', 'val')->toArray();
        $dis2Perilaku = KedisiplinanKedua::where($userIdFilter)->selectRaw('LOWER(perilaku) as val, count(*) as total')->groupBy('val')->pluck('total', 'val')->toArray();
        $dis3Perilaku = KedisiplinanKetiga::where($userIdFilter)->selectRaw('LOWER(perilaku) as val, count(*) as total')->groupBy('val')->pluck('total', 'val')->toArray();

        $chartKedisiplinan = [
            'categories' => ['Hari 1', 'Hari 2', 'Hari 3'],
            'atribut' => [
                'lengkap' => [($dis1Atribut['lengkap'] ?? 0), ($dis2Atribut['lengkap'] ?? 0), ($dis3Atribut['lengkap'] ?? 0)],
                'tidak_lengkap' => [($dis1Atribut['tidak lengkap'] ?? 0), ($dis2Atribut['tidak lengkap'] ?? 0), ($dis3Atribut['tidak lengkap'] ?? 0)],
            ],
            'waktu' => [
                'tepat_waktu' => [($dis1Waktu['tepat waktu'] ?? 0), ($dis2Waktu['tepat waktu'] ?? 0), ($dis3Waktu['tepat waktu'] ?? 0)],
                'terlambat' => [($dis1Waktu['terlambat'] ?? 0), ($dis2Waktu['terlambat'] ?? 0), ($dis3Waktu['terlambat'] ?? 0)],
            ],
            'perilaku' => [
                'sangat_baik' => (($dis1Perilaku['sangat baik'] ?? 0) + ($dis2Perilaku['sangat baik'] ?? 0) + ($dis3Perilaku['sangat baik'] ?? 0)),
                'baik' => (($dis1Perilaku['baik'] ?? 0) + ($dis2Perilaku['baik'] ?? 0) + ($dis3Perilaku['baik'] ?? 0)),
                'cukup' => (($dis1Perilaku['cukup'] ?? 0) + ($dis2Perilaku['cukup'] ?? 0) + ($dis3Perilaku['cukup'] ?? 0)),
                'kurang' => (($dis1Perilaku['kurang'] ?? 0) + ($dis2Perilaku['kurang'] ?? 0) + ($dis3Perilaku['kurang'] ?? 0)),
            ],
        ];

        // 3. Pre-Test & Post-Test Modul 1 s/d 4
        $pretestAvg = [];
        $posttestAvg = [];
        $tuntasCount = [];
        $belumTuntasCount = [];

        for ($m = 1; $m <= 4; $m++) {
            $preQ = HasilTest::where('modul', $m)->where('type', 'pretest')->where($userIdFilter);
            $postQ = HasilTest::where('modul', $m)->where('type', 'posttest')->where($userIdFilter);

            $pretestAvg[] = round((clone $preQ)->avg('skor') ?? 0, 1);
            $posttestAvg[] = round((clone $postQ)->avg('skor') ?? 0, 1);

            $tuntasCount[] = (clone $postQ)->where('skor', '>=', 65)->count();
            $belumTuntasCount[] = (clone $postQ)->where('skor', '<', 65)->count();
        }

        $chartHasilTest = [
            'categories' => ['Modul 1', 'Modul 2', 'Modul 3', 'Modul 4'],
            'pretestAvg' => $pretestAvg,
            'posttestAvg' => $posttestAvg,
            'tuntas' => $tuntasCount,
            'belumTuntas' => $belumTuntasCount,
        ];

        return [
            'chartAbsensi' => $chartAbsensi,
            'chartKedisiplinan' => $chartKedisiplinan,
            'chartHasilTest' => $chartHasilTest,
        ];
    }
}