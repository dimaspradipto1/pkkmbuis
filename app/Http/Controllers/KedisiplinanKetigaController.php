<?php

namespace App\Http\Controllers;

use App\DataTables\KedisiplinanKetigaDataTable;
use App\Models\KedisiplinanKetiga;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KedisiplinanKetigaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(KedisiplinanKetigaDataTable $dataTable)
    {
        return $dataTable->render('pages.kedisiplinanketiga.index');
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
        return view('pages.kedisiplinanketiga.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kelengkapan_atribut' => 'nullable|string',
            'ketepatan_waktu' => 'nullable|string',
            'perilaku' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        KedisiplinanKetiga::create($validated);

        Alert::success('Kedisiplinan berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('kedisiplinanketiga.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kedisiplinanKetiga = KedisiplinanKetiga::findOrFail($id);
        $authUser = Auth::user();
        if ($authUser->role == 'kakakleting') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', $authUser->id)->pluck('id');
            $users = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->orderBy('name')->get();
        } else {
            $users = User::where('role', 'mahasiswa')->orderBy('name')->get();
        }
        return view('pages.kedisiplinanketiga.edit', compact('kedisiplinanKetiga', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kedisiplinanKetiga = KedisiplinanKetiga::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kelengkapan_atribut' => 'nullable|string',
            'ketepatan_waktu' => 'nullable|string',
            'perilaku' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $kedisiplinanKetiga->update($validated);

        Alert::success('Kedisiplinan berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('kedisiplinanketiga.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kedisiplinanKetiga = KedisiplinanKetiga::findOrFail($id);
        $kedisiplinanKetiga->delete();

        Alert::success('Kedisiplinan berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('kedisiplinanketiga.index');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kedisiplinan_ketigas,id',
            'kelengkapan_atribut' => 'nullable|string',
            'ketepatan_waktu' => 'nullable|string',
            'perilaku' => 'nullable|string',
        ]);

        $updateData = array_filter($request->only(['kelengkapan_atribut', 'ketepatan_waktu', 'perilaku']));

        if (!empty($updateData)) {
            KedisiplinanKetiga::whereIn('id', $request->ids)->update($updateData);
            Alert::success('Penilaian massal berhasil.', 'Success')
                ->toToast()
                ->autoClose(3000);
        }

        return redirect()->route('kedisiplinanketiga.index');
    }
}
