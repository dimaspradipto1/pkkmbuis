<?php

namespace App\Http\Controllers;

use App\DataTables\SoalTugasKelompokDataTable;
use App\Models\SoalTugasKelompok;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

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

    public function destroy(string $id)
    {
        $soalTugasKelompok = SoalTugasKelompok::findOrFail($id);
        $soalTugasKelompok->delete();

        Alert::success('Link tugas berhasil dihapus.', 'Deleted')->toToast()->autoClose(3000);
        return redirect()->route('soaltugaskelompok.index');
    }
}
