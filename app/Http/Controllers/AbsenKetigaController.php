<?php

namespace App\Http\Controllers;

use App\DataTables\AbsenKetigaDataTable;
use App\Models\AbsenKetiga;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Storage;

class AbsenKetigaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AbsenKetigaDataTable $dataTable)
    {
        $attachments = \App\Models\AbsenAttachment::where('category', 'absenketiga')->latest()->get();
        $notes = \App\Models\AbsenNote::where('category', 'absenketiga')->latest()->get();
        return $dataTable->render('pages.absenketiga.index', compact('attachments', 'notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authUser = Auth::user();
        if ($authUser->role == 'kakakpendamping') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', $authUser->id)->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
            $users = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->orderBy('name')->get();
        } else {
            $users = User::where('role', 'mahasiswa')->orderBy('name')->get();
        }
        return view('pages.absenketiga.create', compact('users'));
    }

    /**
     * Check current user attendance status.
     */
    public function checkStatus($user_id)
    {
        $absen = AbsenKetiga::where('user_id', $user_id)->first();
        if (!$absen) {
            return response()->json([
                'exists' => false,
                'hadir_pagi' => null,
                'hadir_sore' => null,
                'is_complete' => false
            ]);
        }

        $pagiSet = !empty($absen->hadir_pagi) && $absen->hadir_pagi !== 'Belum Absen';
        $soreSet = !empty($absen->hadir_sore) && $absen->hadir_sore !== 'Belum Absen';

        return response()->json([
            'exists' => true,
            'hadir_pagi' => $absen->hadir_pagi,
            'hadir_sore' => $absen->hadir_sore,
            'is_complete' => ($pagiSet && $soreSet)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $existing = AbsenKetiga::where('user_id', $request->user_id)->first();
        $pagiInput = $request->hadir_pagi;
        $soreInput = $request->hadir_sore;

        if ($existing) {
            $pagiAlreadySet = !empty($existing->hadir_pagi) && $existing->hadir_pagi !== 'Belum Absen';
            $soreAlreadySet = !empty($existing->hadir_sore) && $existing->hadir_sore !== 'Belum Absen';

            if ($pagiAlreadySet && $soreAlreadySet) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['user_id' => 'Pengguna ini sudah memiliki data absensi ketiga lengkap (Pagi & Sore).']);
            }

            if ($pagiInput && $pagiAlreadySet && !$soreInput) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['user_id' => 'Hadir Datang untuk pengguna ini sudah terisi! Silakan pilih Hadir Pulang jika ingin melengkapi absensi.']);
            }

            if ($soreInput && $soreAlreadySet && !$pagiInput) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['user_id' => 'Hadir Pulang untuk pengguna ini sudah terisi!']);
            }

            if (!$pagiInput && !$soreInput) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['user_id' => 'Silakan pilih status Hadir Datang atau Hadir Pulang.']);
            }
        } else {
            if (!$pagiInput && !$soreInput) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['user_id' => 'Silakan pilih status Hadir Datang atau Hadir Pulang.']);
            }
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'hadir_pagi' => 'nullable|string',
            'hadir_sore' => 'nullable|string',
            'catatan' => 'nullable|string',
            'bukti_izin' => 'nullable|file|mimes:png,jpg,jpeg,webp|max:10240',
        ]);

        if ($existing) {
            $validated['hadir_pagi'] = $pagiInput ?? $existing->hadir_pagi ?? 'Belum Absen';
            $validated['hadir_sore'] = $soreInput ?? $existing->hadir_sore ?? 'Belum Absen';

            if ($request->hasFile('bukti_izin')) {
                if (!empty($existing->bukti_izin) && Storage::disk('public')->exists($existing->bukti_izin)) {
                    Storage::disk('public')->delete($existing->bukti_izin);
                }
                $validated['bukti_izin'] = $request->file('bukti_izin')->store('bukti_izin', 'public');
            }

            $isNonHadirPagi = in_array($validated['hadir_pagi'], ['Izin', 'Tidak Hadir']);
            $isNonHadirSore = in_array($validated['hadir_sore'], ['Izin', 'Tidak Hadir']);
            if (!$isNonHadirPagi && !$isNonHadirSore) {
                $validated['catatan'] = null;
            }

            $existing->update($validated);

            // Catat waktu jika belum ada dan status Hadir
            $timeUpdate = [];
            if ($validated['hadir_pagi'] === 'Hadir' && empty($existing->waktu_datang)) {
                $timeUpdate['waktu_datang'] = now();
            }
            if ($validated['hadir_sore'] === 'Hadir' && empty($existing->waktu_pulang)) {
                $timeUpdate['waktu_pulang'] = now();
            }
            if (!empty($timeUpdate)) {
                $existing->update($timeUpdate);
            }

            Alert::success('Absensi berhasil diperbarui.', 'Success')
                ->toToast()
                ->autoClose(3000);
        } else {
            $validated['hadir_pagi'] = $pagiInput ?? 'Belum Absen';
            $validated['hadir_sore'] = $soreInput ?? 'Belum Absen';

            if ($request->hasFile('bukti_izin')) {
                $validated['bukti_izin'] = $request->file('bukti_izin')->store('bukti_izin', 'public');
            }

            $isNonHadirPagi = in_array($validated['hadir_pagi'], ['Izin', 'Tidak Hadir']);
            $isNonHadirSore = in_array($validated['hadir_sore'], ['Izin', 'Tidak Hadir']);
            if (!$isNonHadirPagi && !$isNonHadirSore) {
                $validated['catatan'] = null;
            }

            $absen = AbsenKetiga::create($validated);

            // Catat waktu jika status Hadir
            $timeUpdate = [];
            if (($validated['hadir_pagi'] ?? '') === 'Hadir') {
                $timeUpdate['waktu_datang'] = now();
            }
            if (($validated['hadir_sore'] ?? '') === 'Hadir') {
                $timeUpdate['waktu_pulang'] = now();
            }
            if (!empty($timeUpdate)) {
                $absen->update($timeUpdate);
            }

            Alert::success('Absensi berhasil ditambahkan.', 'Success')
                ->toToast()
                ->autoClose(3000);
        }

        return redirect()->route('absenketiga.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $absenKetiga = AbsenKetiga::findOrFail($id);
        $authUser = Auth::user();
        if ($authUser->role == 'kakakpendamping') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', $authUser->id)->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
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
            'user_id' => 'required|exists:users,id|unique:absen_ketigas,user_id,' . $id,
            'hadir_pagi' => 'nullable|string',
            'hadir_sore' => 'nullable|string',
            'catatan' => 'nullable|string',
            'bukti_izin' => 'nullable|file|mimes:png,jpg,jpeg,webp|max:10240',
        ], [
            'user_id.unique' => 'Pengguna ini sudah memiliki data absensi ketiga.',
        ]);

        $validated['hadir_pagi'] = $request->hadir_pagi ?? 'Belum Absen';
        $validated['hadir_sore'] = $request->hadir_sore ?? 'Belum Absen';

        if ($request->hasFile('bukti_izin')) {
            if ($absenKetiga->bukti_izin) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($absenKetiga->bukti_izin);
            }
            $validated['bukti_izin'] = $request->file('bukti_izin')->store('bukti_izin', 'public');
        }

        $isNonHadirPagi = in_array($validated['hadir_pagi'], ['Izin', 'Tidak Hadir']);
        $isNonHadirSore = in_array($validated['hadir_sore'], ['Izin', 'Tidak Hadir']);
        if (!$isNonHadirPagi && !$isNonHadirSore) {
            $validated['catatan'] = null;
        }

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

    /**
     * Remove the specified resources from storage in bulk.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:absen_ketigas,id',
        ]);

        $absens = AbsenKetiga::whereIn('id', $request->ids)->get();
        foreach ($absens as $absen) {
            if (!empty($absen->bukti_izin) && Storage::disk('public')->exists($absen->bukti_izin)) {
                Storage::disk('public')->delete($absen->bukti_izin);
            }
            $absen->delete();
        }

        Alert::success('Data absensi terpilih berhasil dihapus.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('absenketiga.index');
    }
}
