<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AbsenPertama;
use App\Models\AbsenKedua;
use App\Models\AbsenKetiga;
use App\Models\HasilTest;
use App\Models\SoalTugasKelompok;
use App\Models\KedisiplinanPertama;
use App\Models\KedisiplinanKedua;
use App\Models\KedisiplinanKetiga;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Counts
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();

        // Attendance Stats (Hadir if either pagi or sore is filled)
        $absen1 = AbsenPertama::where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen')->count();
        $absen2 = AbsenKedua::where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen')->count();
        $absen3 = AbsenKetiga::where('hadir_pagi', '!=', 'Belum Absen')->orWhere('hadir_sore', '!=', 'Belum Absen')->count();

        // Test Stats (Count unique users who have filled)
        $pretestCount = HasilTest::where('type', 'pretest')->distinct('user_id')->count();
        $posttestCount = HasilTest::where('type', 'posttest')->distinct('user_id')->count();
        $tugasCount = SoalTugasKelompok::count();

        // Complete Snapshots for Dashboard Tables
        $recentUsers = User::latest()->take(5)->get();

        // Fetch more for comprehensive dashboard view
        $allAbsen1 = AbsenPertama::with('user')->latest('updated_at')->take(10)->get();
        $allAbsen2 = AbsenKedua::with('user')->latest('updated_at')->take(10)->get();
        $allAbsen3 = AbsenKetiga::with('user')->latest('updated_at')->take(10)->get();

        // Fetch Discipline Snapshots
        $allDis1 = KedisiplinanPertama::with('user')->latest('updated_at')->take(10)->get();
        $allDis2 = KedisiplinanKedua::with('user')->latest('updated_at')->take(10)->get();
        $allDis3 = KedisiplinanKetiga::with('user')->latest('updated_at')->take(10)->get();

        $allPretest = HasilTest::with('user')->where('type', 'pretest')->latest()->take(10)->get();
        $allPosttest = HasilTest::with('user')->where('type', 'posttest')->latest()->take(10)->get();
        $allTugas = SoalTugasKelompok::with('user')->latest()->take(10)->get();

        // Specific Module Snapshots
        $allM1 = User::whereHas('hasilTests', fn($q) => $q->where('modul', 1))
                    ->with(['hasilTests' => fn($q) => $q->where('modul', 1)])
                    ->take(10)->get();
        $allM2 = User::whereHas('hasilTests', fn($q) => $q->where('modul', 2))
                    ->with(['hasilTests' => fn($q) => $q->where('modul', 2)])
                    ->take(10)->get();
        $allM3 = User::whereHas('hasilTests', fn($q) => $q->where('modul', 3))
                    ->with(['hasilTests' => fn($q) => $q->where('modul', 3)])
                    ->take(10)->get();
        $allM4 = User::whereHas('hasilTests', fn($q) => $q->where('modul', 4))
                    ->with(['hasilTests' => fn($q) => $q->where('modul', 4)])
                    ->take(10)->get();

        return view('dashboard.index', compact(
            'totalMahasiswa',
            'absen1',
            'absen2',
            'absen3',
            'pretestCount',
            'posttestCount',
            'tugasCount',
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
            'allM4'
        ));
    }
}