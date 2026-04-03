<?php

namespace App\Http\Controllers;

use App\DataTables\SoalPretestKetigaDataTable;
use App\Models\SoalPretestKetiga;
use App\Traits\HasSoalImportTemplate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SoalPretestKetigaController extends Controller
{
    use HasSoalImportTemplate;

    protected $modelClass = SoalPretestKetiga::class;
    public function index(SoalPretestKetigaDataTable $dataTable)
    {
        return $dataTable->render('pages.soalpretestketiga.index');
    }

    public function create()
    {
        return view('pages.soalpretestketiga.create');
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

        SoalPretestKetiga::create($validated);

        Alert::success('Soal berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestketiga.index');
    }

    public function edit(string $id)
    {
        $soalPretestKetiga = SoalPretestKetiga::findOrFail($id);
        return view('pages.soalpretestketiga.edit', compact('soalPretestKetiga'));
    }

    public function update(Request $request, string $id)
    {
        $soalPretestKetiga = SoalPretestKetiga::findOrFail($id);

        $validated = $request->validate([
            'soal'      => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban'   => 'required|in:A,B,C,D',
        ]);

        $soalPretestKetiga->update($validated);

        Alert::success('Soal berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestketiga.index');
    }

    public function destroy(string $id)
    {
        $soalPretestKetiga = SoalPretestKetiga::findOrFail($id);
        $soalPretestKetiga->delete();

        Alert::success('Soal berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestketiga.index');
    }
}
