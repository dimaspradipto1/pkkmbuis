<?php

namespace App\Http\Controllers;

use App\DataTables\SoalPostTestKeduaDataTable;
use App\Models\SoalPostTestKedua;
use App\Traits\HasSoalImportTemplate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SoalPostTestKeduaController extends Controller
{
    use HasSoalImportTemplate;

    protected $modelClass = SoalPostTestKedua::class;
    public function index(SoalPostTestKeduaDataTable $dataTable)
    {
        return $dataTable->render('pages.soalposttestkedua.index');
    }

    public function create()
    {
        return view('pages.soalposttestkedua.create');
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

        SoalPostTestKedua::create($validated);

        Alert::success('Soal berhasil ditambahkan.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestkedua.index');
    }

    public function edit(string $id)
    {
        $soalPostTestKedua = SoalPostTestKedua::findOrFail($id);
        return view('pages.soalposttestkedua.edit', compact('soalPostTestKedua'));
    }

    public function update(Request $request, string $id)
    {
        $soalPostTestKedua = SoalPostTestKedua::findOrFail($id);

        $validated = $request->validate([
            'soal'      => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban'   => 'required|in:A,B,C,D',
        ]);

        $soalPostTestKedua->update($validated);

        Alert::success('Soal berhasil diperbarui.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestkedua.index');
    }

    public function destroy(string $id)
    {
        $soalPostTestKedua = SoalPostTestKedua::findOrFail($id);
        $soalPostTestKedua->delete();

        Alert::success('Soal berhasil dihapus.', 'Deleted')->toToast()->autoClose(3000);
        return redirect()->route('soalposttestkedua.index');
    }
}
