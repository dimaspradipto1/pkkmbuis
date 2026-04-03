<?php

namespace App\Http\Controllers;

use App\DataTables\SoalPostTestPertamaDataTable;
use App\Models\SoalPostTestPertama;
use App\Traits\HasSoalImportTemplate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SoalPostTestPertamaController extends Controller
{
    use HasSoalImportTemplate;

    protected $modelClass = SoalPostTestPertama::class;
    public function index(SoalPostTestPertamaDataTable $dataTable)
    {
        return $dataTable->render('pages.soalposttestpertama.index');
    }

    public function create()
    {
        return view('pages.soalposttestpertama.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'soal'      => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban'   => 'required|in:A,B,C,D',
        ]);

        SoalPostTestPertama::create($validated);

        Alert::success('Soal berhasil ditambahkan.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestpertama.index');
    }

    public function edit(string $id)
    {
        $soalPostTestPertama = SoalPostTestPertama::findOrFail($id);
        return view('pages.soalposttestpertama.edit', compact('soalPostTestPertama'));
    }

    public function update(Request $request, string $id)
    {
        $soalPostTestPertama = SoalPostTestPertama::findOrFail($id);

        $validated = $request->validate([
            'soal'      => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban'   => 'required|in:A,B,C,D',
        ]);

        $soalPostTestPertama->update($validated);

        Alert::success('Soal berhasil diperbarui.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestpertama.index');
    }

    public function destroy(string $id)
    {
        $soalPostTestPertama = SoalPostTestPertama::findOrFail($id);
        $soalPostTestPertama->delete();

        Alert::success('Soal berhasil dihapus.', 'Deleted')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestpertama.index');
    }
}
