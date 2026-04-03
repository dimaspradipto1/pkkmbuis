<?php

namespace App\Http\Controllers;

use App\DataTables\SoalPretestKeempatDataTable;
use App\Models\SoalPretestKeempat;
use App\Traits\HasSoalImportTemplate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SoalPretestKeempatController extends Controller
{
    use HasSoalImportTemplate;

    protected $modelClass = SoalPretestKeempat::class;
    public function index(SoalPretestKeempatDataTable $dataTable)
    {
        return $dataTable->render('pages.soalpretestkeempat.index');
    }

    public function create()
    {
        return view('pages.soalpretestkeempat.create');
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

        SoalPretestKeempat::create($validated);

        Alert::success('Soal berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestkeempat.index');
    }

    public function edit(string $id)
    {
        $soalPretestKeempat = SoalPretestKeempat::findOrFail($id);
        return view('pages.soalpretestkeempat.edit', compact('soalPretestKeempat'));
    }

    public function update(Request $request, string $id)
    {
        $soalPretestKeempat = SoalPretestKeempat::findOrFail($id);

        $validated = $request->validate([
            'soal'      => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban'   => 'required|in:A,B,C,D',
        ]);

        $soalPretestKeempat->update($validated);

        Alert::success('Soal berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestkeempat.index');
    }

    public function destroy(string $id)
    {
        $soalPretestKeempat = SoalPretestKeempat::findOrFail($id);
        $soalPretestKeempat->delete();

        Alert::success('Soal berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestkeempat.index');
    }
}
