<?php

namespace App\Http\Controllers;

use App\DataTables\AbsenKeduaDataTable;
use App\Models\AbsenKedua;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AbsenKeduaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AbsenKeduaDataTable $dataTable)
    {
        return $dataTable->render('pages.absenkedua.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('pages.absenkedua.create', compact('users'));
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

        AbsenKedua::create($validated);

        Alert::success('Absensi berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('absenkedua.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $absenKedua = AbsenKedua::findOrFail($id);
        $users = User::all();
        return view('pages.absenkedua.edit', compact('absenKedua', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $absenKedua = AbsenKedua::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'hadir_pagi' => 'nullable|string',
            'hadir_sore' => 'nullable|string',
        ]);

        $absenKedua->update($validated);

        Alert::success('Absensi berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('absenkedua.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $absenKedua = AbsenKedua::findOrFail($id);
        $absenKedua->delete();

        Alert::success('Absensi berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('absenkedua.index');
    }
}
