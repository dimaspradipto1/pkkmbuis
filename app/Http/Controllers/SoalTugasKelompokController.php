<?php

namespace App\Http\Controllers;

use App\DataTables\SoalTugasKelompokDataTable;
use App\Models\SoalTugasKelompok;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;

class SoalTugasKelompokController extends Controller
{
    public function index(SoalTugasKelompokDataTable $dataTable)
    {
        return $dataTable->render('pages.soaltugaskelompok.index');
    }

    public function create()
    {
        return view('pages.soaltugaskelompok.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'link_tugas' => 'required|url|max:2048',
        ]);

        SoalTugasKelompok::create($validated);

        Alert::success('Link tugas berhasil ditambahkan.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('soaltugaskelompok.index');
    }

    public function edit(string $id)
    {
        $soalTugasKelompok = SoalTugasKelompok::findOrFail($id);
        return view('pages.soaltugaskelompok.edit', compact('soalTugasKelompok'));
    }

    public function update(Request $request, string $id)
    {
        $soalTugasKelompok = SoalTugasKelompok::findOrFail($id);

        $validated = $request->validate([
            'link_tugas' => 'required|url|max:2048',
        ]);

        $soalTugasKelompok->update($validated);

        Alert::success('Link tugas berhasil diperbarui.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('soaltugaskelompok.index');
    }

    public function updateNilai(Request $request, string $id)
    {
        try {
            $soalTugasKelompok = SoalTugasKelompok::findOrFail($id);

            $nilai = $request->input('nilai');
            
            if ($nilai === null || $nilai === '') {
                $soalTugasKelompok->nilai = null;
            } else {
                if (!is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
                    throw new \Exception('Nilai harus berupa angka 0-100.');
                }
                $soalTugasKelompok->nilai = $nilai;
            }

            $soalTugasKelompok->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Nilai tugas berhasil disimpan.']);
            }

            Alert::success('Nilai tugas berhasil disimpan.', 'Success')->toToast()->autoClose(3000);
            return redirect()->back();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }

            Alert::error('Gagal menyimpan: ' . $e->getMessage(), 'Error')->toToast()->autoClose(5000);
            return redirect()->back();
        }
    }

    public function destroy(string $id)
    {
        $soalTugasKelompok = SoalTugasKelompok::findOrFail($id);
        $soalTugasKelompok->delete();

        Alert::success('Link tugas berhasil dihapus.', 'Deleted')->toToast()->autoClose(3000);
        return redirect()->route('soaltugaskelompok.index');
    }
}
