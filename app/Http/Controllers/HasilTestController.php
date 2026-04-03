<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\HasilTest;
use App\Models\User;
use App\DataTables\HasilTestDataTable;
use App\Imports\HasilTestImport;
use App\Exports\HasilTestExport;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class HasilTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(HasilTestDataTable $dataTable)
    {
        return $dataTable->render('pages.hasiltest.index');
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
