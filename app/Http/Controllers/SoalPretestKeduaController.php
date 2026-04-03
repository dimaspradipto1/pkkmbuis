<?php

namespace App\Http\Controllers;

use App\DataTables\SoalPretestKeduaDataTable;
use App\Models\SoalPretestKedua;
use App\Traits\HasSoalImportTemplate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SoalPretestKeduaController extends Controller
{
    use HasSoalImportTemplate;

    protected $modelClass = SoalPretestKedua::class;
    public function index(SoalPretestKeduaDataTable $dataTable)
    {
        return $dataTable->render('pages.soalpretestkedua.index');
    }

    public function create()
    {
        return view('pages.soalpretestkedua.create');
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

        SoalPretestKedua::create($validated);

        Alert::success('Soal berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestkedua.index');
    }

    public function edit(string $id)
    {
        $soalPretestKedua = SoalPretestKedua::findOrFail($id);
        return view('pages.soalpretestkedua.edit', compact('soalPretestKedua'));
    }

    public function update(Request $request, string $id)
    {
        $soalPretestKedua = SoalPretestKedua::findOrFail($id);

        $validated = $request->validate([
            'soal'      => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban'   => 'required|in:A,B,C,D',
        ]);

        $soalPretestKedua->update($validated);

        Alert::success('Soal berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestkedua.index');
    }

    public function destroy(string $id)
    {
        $soalPretestKedua = SoalPretestKedua::findOrFail($id);
        $soalPretestKedua->delete();

        Alert::success('Soal berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestkedua.index');
    }
}
