<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\HasilTest;
use App\Models\User;
use App\DataTables\HasilTestAbsensiDataTable;
use App\DataTables\HasilTestKedisiplinanDataTable;
use App\DataTables\HasilTestPenilaianDataTable;
use App\Imports\HasilTestImport;
use App\Exports\HasilTestExport;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

use App\DataTables\HasilModulTestDataTable;
use App\Exports\HasilModulTestExport;
use App\Models\Kelompok;
use Illuminate\Support\Facades\Auth;

class HasilTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        HasilTestAbsensiDataTable $absensiDataTable,
        HasilTestKedisiplinanDataTable $kedisiplinanDataTable,
        HasilTestPenilaianDataTable $penilaianDataTable
    ) {
        if (request()->ajax()) {
            $table = request()->get('table');
            if ($table === 'kedisiplinan') {
                return $kedisiplinanDataTable->ajax();
            }
            if ($table === 'penilaian') {
                return $penilaianDataTable->ajax();
            }
            return $absensiDataTable->ajax();
        }

        return view('pages.hasiltest.index', [
            'absensiTable' => $absensiDataTable->html(),
            'kedisiplinanTable' => $kedisiplinanDataTable->html(),
            'penilaianTable' => $penilaianDataTable->html(),
        ]);
    }

    /**
     * Display specific module Pretest or Posttest results.
     */
    public function showModul(string $type, int $modul, HasilModulTestDataTable $dataTable)
    {
        $type = strtolower($type);
        if (!in_array($type, ['pretest', 'posttest']) || $modul < 1 || $modul > 4) {
            abort(404);
        }

        if (Auth::user()->role === 'mahasiswa') {
            abort(403);
        }

        $dataTable->setTypeAndModul($type, $modul);

        $authUser = Auth::user();
        if ($authUser->role === 'kakakpendamping') {
            $myKelompokIds = Kelompok::where('pendamping_id', $authUser->id)
                ->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))
                ->pluck('id');
            $targetUserIds = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->pluck('id');
            $totalMahasiswa = $targetUserIds->count();
            $testResults = HasilTest::where('type', $type)->where('modul', $modul)->whereIn('user_id', $targetUserIds);
        } elseif ($authUser->role === 'dosenpendamping') {
            $myKelompokIds = Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
            $targetUserIds = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->pluck('id');
            $totalMahasiswa = $targetUserIds->count();
            $testResults = HasilTest::where('type', $type)->where('modul', $modul)->whereIn('user_id', $targetUserIds);
        } else {
            $totalMahasiswa = User::where('role', 'mahasiswa')->count();
            $testResults = HasilTest::where('type', $type)->where('modul', $modul);
        }

        $sudahMengerjakan = (clone $testResults)->count();
        $belumMengerjakan = max(0, $totalMahasiswa - $sudahMengerjakan);
        $rataRata = $sudahMengerjakan > 0 ? round((clone $testResults)->avg('skor'), 1) : 0;
        $tuntasCount = (clone $testResults)->where('skor', '>=', 65)->count();
        $tidakTuntasCount = (clone $testResults)->where('skor', '<', 65)->count();

        return $dataTable->render('pages.hasiltest.modul', compact(
            'type',
            'modul',
            'totalMahasiswa',
            'sudahMengerjakan',
            'belumMengerjakan',
            'rataRata',
            'tuntasCount',
            'tidakTuntasCount'
        ));
    }

    /**
     * Reset a single test score.
     */
    public function resetSingle(string $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
            abort(403);
        }

        $hasilTest = HasilTest::with('user')->findOrFail($id);
        $userName = $hasilTest->user?->name ?? 'Mahasiswa';
        $type = strtoupper($hasilTest->type);
        $modul = $hasilTest->modul;

        $hasilTest->delete();

        Alert::success('Berhasil!', "Nilai {$type} Modul {$modul} untuk {$userName} telah berhasil direset. Mahasiswa dapat mengerjakan ulang.")->toToast()->autoClose(3500);

        return redirect()->back();
    }

    /**
     * Bulk reset module test scores by result IDs.
     */
    public function bulkResetModul(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
            abort(403);
        }

        $ids = $request->input('ids', []);

        if (empty($ids)) {
            Alert::error('Gagal!', 'Tidak ada data nilai mahasiswa yang dipilih.')->toToast()->autoClose(3000);
            return redirect()->back();
        }

        $count = count($ids);
        HasilTest::whereIn('id', $ids)->delete();

        Alert::success('Berhasil!', "Sebanyak {$count} nilai test mahasiswa berhasil direset.")->toToast()->autoClose(3000);

        return redirect()->back();
    }

    /**
     * Export specific module Pretest or Posttest results to Excel.
     */
    public function exportModul(string $type, int $modul)
    {
        $type = strtolower($type);
        $fileName = 'hasil_' . $type . '_modul_' . $modul . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new HasilModulTestExport($type, $modul), $fileName);
    }

    /**
     * Export all student test results.
     */
    public function export()
    {
        return Excel::download(new HasilTestExport, 'hasil_test_mahasiswa_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * Remove (Reset) the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hasilTest = HasilTest::findOrFail($id);
        $hasilTest->delete();

        Alert::success('Berhasil!', 'Hasil test telah direset.')->toToast()->autoClose(3000);

        return redirect()->back();
    }

    public function bulkReset(Request $request)
    {
        $userIds = $request->input('ids');

        if (empty($userIds)) {
            Alert::error('Gagal!', 'Tidak ada mahasiswa yang dipilih.')->toToast()->autoClose(3000);
            return redirect()->back();
        }

        HasilTest::whereIn('user_id', $userIds)->delete();

        Alert::success('Berhasil!', count($userIds) . ' data mahasiswa telah direset.')->toToast()->autoClose(3000);

        return redirect()->back();
    }

    public function resetByUser(\App\Models\User $user)
    {
        $deleted = HasilTest::where('user_id', $user->id)->delete();

        if ($deleted) {
            Alert::success('Berhasil!', 'Seluruh progres akademik untuk ' . $user->name . ' telah berhasil direset.')->toToast()->autoClose(3000);
        } else {
            Alert::info('Info', 'Mahasiswa ini belum memiliki data progres akademik untuk direset.')->toToast()->autoClose(3000);
        }

        return redirect()->back();
    }
}
