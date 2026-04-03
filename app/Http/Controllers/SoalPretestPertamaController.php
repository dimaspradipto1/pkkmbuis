<?php

namespace App\Http\Controllers;

use App\DataTables\SoalPretestPertamaDataTable;
use App\Models\SoalPretestPertama;
use App\Traits\HasSoalImportTemplate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SoalPretestPertamaController extends Controller
{
    use HasSoalImportTemplate;

    protected $modelClass = SoalPretestPertama::class;

    /**
     * Display a listing of the resource.
     */
    public function index(SoalPretestPertamaDataTable $dataTable)
    {
        return $dataTable->render('pages.soalpretestpertama.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.soalpretestpertama.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

        SoalPretestPertama::create($validated);

        Alert::success('Soal berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestpertama.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $soalPretestPertama = SoalPretestPertama::findOrFail($id);
        return view('pages.soalpretestpertama.edit', compact('soalPretestPertama'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $soalPretestPertama = SoalPretestPertama::findOrFail($id);

        $validated = $request->validate([
            'soal'      => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban'   => 'required|in:A,B,C,D',
        ]);

        $soalPretestPertama->update($validated);

        Alert::success('Soal berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestpertama.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $soalPretestPertama = SoalPretestPertama::findOrFail($id);
        $soalPretestPertama->delete();

        Alert::success('Soal berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('soalpretestpertama.index');
    }
}
