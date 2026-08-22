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
    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\KedisiplinanExport(\App\Models\Kedisiplinanketiga::class),
            'Kedisiplinan_Hari_Ketiga_' . date('Ymd_His') . '.xlsx'
        );
    }

    public function index(KedisiplinanKetigaDataTable $dataTable)
    {
        $authUser = Auth::user();
        if ($authUser->role == 'kakakpendamping') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', $authUser->id)
                ->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))
                ->pluck('id');
            $targetUserIds = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->pluck('id');
            $totalMahasiswa = $targetUserIds->count();

            $sudahAdaData = KedisiplinanKetiga::whereIn('user_id', $targetUserIds)
                ->where(function($q) {
                    $q->where(function($sub) {
                        $sub->whereNotNull('kelengkapan_atribut')->where('kelengkapan_atribut', '!=', '')->where('kelengkapan_atribut', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('ketepatan_waktu')->where('ketepatan_waktu', '!=', '')->where('ketepatan_waktu', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('perilaku')->where('perilaku', '!=', '')->where('perilaku', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('catatan')->where('catatan', '!=', '')->where('catatan', '!=', '-');
                    });
                })->count();

            $belumAdaData = max(0, $totalMahasiswa - $sudahAdaData);
        } elseif ($authUser->role == 'dosenpendamping') {
            $myKelompokIds = \App\Models\Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
            $targetUserIds = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->pluck('id');
            $totalMahasiswa = $targetUserIds->count();

            $sudahAdaData = KedisiplinanKetiga::whereIn('user_id', $targetUserIds)
                ->where(function($q) {
                    $q->where(function($sub) {
                        $sub->whereNotNull('kelengkapan_atribut')->where('kelengkapan_atribut', '!=', '')->where('kelengkapan_atribut', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('ketepatan_waktu')->where('ketepatan_waktu', '!=', '')->where('ketepatan_waktu', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('perilaku')->where('perilaku', '!=', '')->where('perilaku', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('catatan')->where('catatan', '!=', '')->where('catatan', '!=', '-');
                    });
                })->count();

            $belumAdaData = max(0, $totalMahasiswa - $sudahAdaData);
        } elseif ($authUser->role == 'mahasiswa') {
            $totalMahasiswa = 1;
            $hasData = KedisiplinanKetiga::where('user_id', $authUser->id)
                ->where(function($q) {
                    $q->where(function($sub) {
                        $sub->whereNotNull('kelengkapan_atribut')->where('kelengkapan_atribut', '!=', '')->where('kelengkapan_atribut', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('ketepatan_waktu')->where('ketepatan_waktu', '!=', '')->where('ketepatan_waktu', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('perilaku')->where('perilaku', '!=', '')->where('perilaku', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('catatan')->where('catatan', '!=', '')->where('catatan', '!=', '-');
                    });
                })->exists();

            $sudahAdaData = $hasData ? 1 : 0;
            $belumAdaData = $hasData ? 0 : 1;
        } else {
            $totalMahasiswa = User::where('role', 'mahasiswa')->count();
            $sudahAdaData = KedisiplinanKetiga::whereHas('user', fn($q) => $q->where('role', 'mahasiswa'))
                ->where(function($q) {
                    $q->where(function($sub) {
                        $sub->whereNotNull('kelengkapan_atribut')->where('kelengkapan_atribut', '!=', '')->where('kelengkapan_atribut', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('ketepatan_waktu')->where('ketepatan_waktu', '!=', '')->where('ketepatan_waktu', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('perilaku')->where('perilaku', '!=', '')->where('perilaku', '!=', '-');
                    })->orWhere(function($sub) {
                        $sub->whereNotNull('catatan')->where('catatan', '!=', '')->where('catatan', '!=', '-');
                    });
                })->count();

            $belumAdaData = max(0, $totalMahasiswa - $sudahAdaData);
        }

        $attachments = \App\Models\KedisiplinanAttachment::where('category', 'kedisiplinanketiga')->latest()->get();
        $notes = \App\Models\KedisiplinanNote::where('category', 'kedisiplinanketiga')->latest()->get();

        return $dataTable->render('pages.kedisiplinanketiga.index', compact('attachments', 'notes', 'totalMahasiswa', 'sudahAdaData', 'belumAdaData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authUser = Auth::user();
        if ($authUser->role == 'kakakpendamping') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', $authUser->id)->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
            $users = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->with('kedisiplinanKetiga')->orderBy('name')->get();
        } else {
            $users = User::where('role', 'mahasiswa')->with('kedisiplinanKetiga')->orderBy('name')->get();
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

        KedisiplinanKetiga::updateOrCreate(
            ['user_id' => $validated['user_id']],
            $validated
        );

        Alert::success('Kedisiplinan berhasil disimpan.', 'Success')
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
        if ($authUser->role == 'kakakpendamping') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', $authUser->id)->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
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

        $existingOther = KedisiplinanKetiga::where('user_id', $validated['user_id'])
            ->where('id', '!=', $id)
            ->first();

        if ($existingOther) {
            $existingOther->update($validated);
            $kedisiplinanKetiga->delete();
        } else {
            $kedisiplinanKetiga->update($validated);
        }

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

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kedisiplinan_ketigas,id',
        ]);

        KedisiplinanKetiga::whereIn('id', $request->ids)->delete();

        Alert::success('Data kedisiplinan terpilih berhasil dihapus.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('kedisiplinanketiga.index');
    }
}
