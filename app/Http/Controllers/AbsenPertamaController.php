<?php

namespace App\Http\Controllers;

use App\DataTables\AbsenPertamaDataTable;
use App\Models\AbsenPertama;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AbsenPertamaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AbsenPertamaDataTable $dataTable)
    {
        return $dataTable->render('pages.absenpertama.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('pages.absenpertama.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'hadir_pagi' => 'nullable|string',
            'hadir_sore' => 'nullable|string',
        ]);

        AbsenPertama::create($validated);

        Alert::success('Absensi berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('absenpertama.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $absenPertama = AbsenPertama::findOrFail($id);
        $users = User::all();
        return view('pages.absenpertama.edit', compact('absenPertama', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $absenPertama = AbsenPertama::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'hadir_pagi' => 'nullable|string',
            'hadir_sore' => 'nullable|string',
        ]);

        $absenPertama->update($validated);

        Alert::success('Absensi berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('absenpertama.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $absenPertama = AbsenPertama::findOrFail($id);
        $absenPertama->delete();

        Alert::success('Absensi berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('absenpertama.index');
    }
}
