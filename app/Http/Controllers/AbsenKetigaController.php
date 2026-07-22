<?php

namespace App\Http\Controllers;

use App\DataTables\AbsenKetigaDataTable;
use App\Models\AbsenKetiga;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AbsenKetigaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AbsenKetigaDataTable $dataTable)
    {
        return $dataTable->render('pages.absenketiga.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authUser = Auth::user();
        if ($authUser->role == 'kakakleting') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', $authUser->id)->pluck('id');
            $users = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->orderBy('name')->get();
        } else {
            $users = User::where('role', 'mahasiswa')->orderBy('name')->get();
        }
        return view('pages.absenketiga.create', compact('users'));
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

        AbsenKetiga::create($validated);

        Alert::success('Absensi berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('absenketiga.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $absenKetiga = AbsenKetiga::findOrFail($id);
        $authUser = Auth::user();
        if ($authUser->role == 'kakakleting') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', $authUser->id)->pluck('id');
            $users = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->orderBy('name')->get();
        } else {
            $users = User::where('role', 'mahasiswa')->orderBy('name')->get();
        }
        return view('pages.absenketiga.edit', compact('absenKetiga', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $absenKetiga = AbsenKetiga::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'hadir_pagi' => 'nullable|string',
            'hadir_sore' => 'nullable|string',
        ]);

        $absenKetiga->update($validated);

        Alert::success('Absensi berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('absenketiga.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $absenKetiga = AbsenKetiga::findOrFail($id);
        $absenKetiga->delete();

        Alert::success('Absensi berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('absenketiga.index');
    }
}
