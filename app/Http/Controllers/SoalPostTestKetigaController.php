<?php

namespace App\Http\Controllers;

use App\DataTables\SoalPostTestKetigaDataTable;
use App\Models\SoalPostTestKetiga;
use App\Traits\HasSoalImportTemplate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SoalPostTestKetigaController extends Controller
{
    use HasSoalImportTemplate;

    protected $modelClass = SoalPostTestKetiga::class;
    public function index(SoalPostTestKetigaDataTable $dataTable)
    {
        return $dataTable->render('pages.soalposttestketiga.index');
    }

    public function create()
    {
        return view('pages.soalposttestketiga.create');
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

        SoalPostTestKetiga::create($validated);

        Alert::success('Soal berhasil ditambahkan.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestketiga.index');
    }

    public function edit(string $id)
    {
        $soalPostTestKetiga = SoalPostTestKetiga::findOrFail($id);
        return view('pages.soalposttestketiga.edit', compact('soalPostTestKetiga'));
    }

    public function update(Request $request, string $id)
    {
        $soalPostTestKetiga = SoalPostTestKetiga::findOrFail($id);

        $validated = $request->validate([
            'soal'      => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban'   => 'required|in:A,B,C,D',
        ]);

        $soalPostTestKetiga->update($validated);

        Alert::success('Soal berhasil diperbarui.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestketiga.index');
    }

    public function destroy(string $id)
    {
        $soalPostTestKetiga = SoalPostTestKetiga::findOrFail($id);
        $soalPostTestKetiga->delete();

        Alert::success('Soal berhasil dihapus.', 'Deleted')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestketiga.index');
    }
}
