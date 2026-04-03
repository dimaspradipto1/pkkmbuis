<?php

namespace App\Http\Controllers;

use App\DataTables\SoalPostTestKeempatDataTable;
use App\Models\SoalPostTestKeempat;
use App\Traits\HasSoalImportTemplate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SoalPostTestKeempatController extends Controller
{
    use HasSoalImportTemplate;

    protected $modelClass = SoalPostTestKeempat::class;
    public function index(SoalPostTestKeempatDataTable $dataTable)
    {
        return $dataTable->render('pages.soalposttestkeempat.index');
    }

    public function create()
    {
        return view('pages.soalposttestkeempat.create');
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

        SoalPostTestKeempat::create($validated);

        Alert::success('Soal berhasil ditambahkan.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestkeempat.index');
    }

    public function edit(string $id)
    {
        $soalPostTestKeempat = SoalPostTestKeempat::findOrFail($id);
        return view('pages.soalposttestkeempat.edit', compact('soalPostTestKeempat'));
    }

    public function update(Request $request, string $id)
    {
        $soalPostTestKeempat = SoalPostTestKeempat::findOrFail($id);

        $validated = $request->validate([
            'soal'      => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban'   => 'required|in:A,B,C,D',
        ]);

        $soalPostTestKeempat->update($validated);

        Alert::success('Soal berhasil diperbarui.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestkeempat.index');
    }

    public function destroy(string $id)
    {
        $soalPostTestKeempat = SoalPostTestKeempat::findOrFail($id);
        $soalPostTestKeempat->delete();

        Alert::success('Soal berhasil dihapus.', 'Deleted')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestkeempat.index');
    }
}
