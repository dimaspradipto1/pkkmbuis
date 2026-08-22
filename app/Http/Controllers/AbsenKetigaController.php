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
    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AbsensiExport(\App\Models\AbsenKetiga::class),
            'Absensi_Hari_Ketiga_' . date('Ymd_His') . '.xlsx'
        );
    }

    public function index(AbsenKetigaDataTable $dataTable)
    {
        $authUser = Auth::user();
        if ($authUser->role == 'kakakpendamping') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', $authUser->id)
                ->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))
                ->pluck('id');
            $targetUserIds = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->pluck('id');
            $totalMahasiswa = $targetUserIds->count();

            $sudahDatang = AbsenKetiga::whereIn('user_id', $targetUserIds)
                ->where('hadir_pagi', '!=', 'Belum Absen')
                ->whereNotNull('hadir_pagi')
                ->count();

            $sudahPulang = AbsenKetiga::whereIn('user_id', $targetUserIds)
                ->where('hadir_sore', '!=', 'Belum Absen')
                ->whereNotNull('hadir_sore')
                ->count();

            $sudahAbsen = AbsenKetiga::whereIn('user_id', $targetUserIds)
                ->where(function($q) {
                    $q->where(function($sub) {
                        $sub->where('hadir_pagi', '!=', 'Belum Absen')->whereNotNull('hadir_pagi');
                    })->orWhere(function($sub) {
                        $sub->where('hadir_sore', '!=', 'Belum Absen')->whereNotNull('hadir_sore');
                    });
                })->count();

            $belumAbsen = max(0, $totalMahasiswa - $sudahAbsen);
        } elseif ($authUser->role == 'dosenpendamping') {
            $myKelompokIds = \App\Models\Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
            $targetUserIds = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->pluck('id');
            $totalMahasiswa = $targetUserIds->count();

            $sudahDatang = AbsenKetiga::whereIn('user_id', $targetUserIds)
                ->where('hadir_pagi', '!=', 'Belum Absen')
                ->whereNotNull('hadir_pagi')
                ->count();

            $sudahPulang = AbsenKetiga::whereIn('user_id', $targetUserIds)
                ->where('hadir_sore', '!=', 'Belum Absen')
                ->whereNotNull('hadir_sore')
                ->count();

            $sudahAbsen = AbsenKetiga::whereIn('user_id', $targetUserIds)
                ->where(function($q) {
                    $q->where(function($sub) {
                        $sub->where('hadir_pagi', '!=', 'Belum Absen')->whereNotNull('hadir_pagi');
                    })->orWhere(function($sub) {
                        $sub->where('hadir_sore', '!=', 'Belum Absen')->whereNotNull('hadir_sore');
                    });
                })->count();

            $belumAbsen = max(0, $totalMahasiswa - $sudahAbsen);
        } elseif ($authUser->role == 'mahasiswa') {
            $totalMahasiswa = 1;
            $absen = AbsenKetiga::where('user_id', $authUser->id)->first();
            $sudahDatang = ($absen && $absen->hadir_pagi != 'Belum Absen' && !empty($absen->hadir_pagi)) ? 1 : 0;
            $sudahPulang = ($absen && $absen->hadir_sore != 'Belum Absen' && !empty($absen->hadir_sore)) ? 1 : 0;
            $belumAbsen = ($sudahDatang == 0 && $sudahPulang == 0) ? 1 : 0;
        } else {
            $totalMahasiswa = User::where('role', 'mahasiswa')->count();
            
            $sudahDatang = AbsenKetiga::where('hadir_pagi', '!=', 'Belum Absen')
                ->whereNotNull('hadir_pagi')
                ->count();

            $sudahPulang = AbsenKetiga::where('hadir_sore', '!=', 'Belum Absen')
                ->whereNotNull('hadir_sore')
                ->count();

            $sudahAbsen = AbsenKetiga::where(function($q) {
                    $q->where(function($sub) {
                        $sub->where('hadir_pagi', '!=', 'Belum Absen')->whereNotNull('hadir_pagi');
                    })->orWhere(function($sub) {
                        $sub->where('hadir_sore', '!=', 'Belum Absen')->whereNotNull('hadir_sore');
                    });
                })
                ->count();

            $belumAbsen = max(0, $totalMahasiswa - $sudahAbsen);
        }

        $attachments = \App\Models\AbsenAttachment::where('category', 'absenketiga')->latest()->get();
        $notes = \App\Models\AbsenNote::where('category', 'absenketiga')->latest()->get();
        return $dataTable->render('pages.absenketiga.index', compact('attachments', 'notes', 'totalMahasiswa', 'sudahDatang', 'sudahPulang', 'belumAbsen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authUser = Auth::user();
        if ($authUser->role == 'kakakpendamping') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', $authUser->id)->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
            $users = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->with('kelompok')->orderBy('name')->get();
        } elseif ($authUser->role == 'dosenpendamping') {
            $myKelompokIds = \App\Models\Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
            $users = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->with('kelompok')->orderBy('name')->get();
        } else {
            $users = User::where('role', 'mahasiswa')->with('kelompok')->orderBy('name')->get();
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
                    ->withErrors(['user_id' => 'Pengguna ini sudah memiliki data absensi ketiga lengkap (Datang & Pulang).']);
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
            'catatan_datang' => 'nullable|string',
            'catatan_pulang' => 'nullable|string',
            'bukti_izin' => 'nullable|file|mimes:png,jpg,jpeg,webp|max:10240',
        ]);

        $notes = [];
        if (!empty($validated['catatan_datang'])) {
            $notes[] = 'Datang: ' . $validated['catatan_datang'];
        }
        if (!empty($validated['catatan_pulang'])) {
            $notes[] = 'Pulang: ' . $validated['catatan_pulang'];
        }
        if (!empty($validated['catatan']) && empty($notes)) {
            $notes[] = $validated['catatan'];
        }
        $validated['catatan'] = implode(' | ', $notes);

        if ($existing) {
            $validated['hadir_pagi'] = $pagiInput ?? $existing->hadir_pagi ?? 'Belum Absen';
            $validated['hadir_sore'] = $soreInput ?? $existing->hadir_sore ?? 'Belum Absen';

            if ($request->hasFile('bukti_izin')) {
                if (!empty($existing->bukti_izin) && Storage::disk('public')->exists($existing->bukti_izin)) {
                    Storage::disk('public')->delete($existing->bukti_izin);
                }
                $validated['bukti_izin'] = $request->file('bukti_izin')->store('bukti_izin', 'public');
            }



            // Catat waktu untuk semua status (Hadir/Izin/Tidak Hadir) jika belum ada
            if (!empty($validated['hadir_pagi']) && $validated['hadir_pagi'] !== 'Belum Absen' && empty($existing->waktu_datang)) {
                $validated['waktu_datang'] = now();
            }
            if (!empty($validated['hadir_sore']) && $validated['hadir_sore'] !== 'Belum Absen' && empty($existing->waktu_pulang)) {
                $validated['waktu_pulang'] = now();
            }

            $existing->update($validated);

            Alert::success('Absensi berhasil diperbarui.', 'Success')
                ->toToast()
                ->autoClose(3000);
        } else {
            $validated['hadir_pagi'] = $pagiInput ?? 'Belum Absen';
            $validated['hadir_sore'] = $soreInput ?? 'Belum Absen';

            if ($request->hasFile('bukti_izin')) {
                $validated['bukti_izin'] = $request->file('bukti_izin')->store('bukti_izin', 'public');
            }



            // Catat waktu untuk semua status (Hadir/Izin/Tidak Hadir)
            if (!empty($validated['hadir_pagi']) && $validated['hadir_pagi'] !== 'Belum Absen') {
                $validated['waktu_datang'] = now();
            }
            if (!empty($validated['hadir_sore']) && $validated['hadir_sore'] !== 'Belum Absen') {
                $validated['waktu_pulang'] = now();
            }

            $absen = AbsenKetiga::create($validated);

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
            $users = User::where('role', 'mahasiswa')->where(fn($q) => $q->whereIn('kelompok_id', $myKelompokIds)->orWhere('id', $absenKetiga->user_id))->with('kelompok')->orderBy('name')->get();
        } elseif ($authUser->role == 'dosenpendamping') {
            $myKelompokIds = \App\Models\Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
            $users = User::where('role', 'mahasiswa')->where(fn($q) => $q->whereIn('kelompok_id', $myKelompokIds)->orWhere('id', $absenKetiga->user_id))->with('kelompok')->orderBy('name')->get();
        } else {
            $users = User::where('role', 'mahasiswa')->with('kelompok')->orderBy('name')->get();
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
            'catatan_datang' => 'nullable|string',
            'catatan_pulang' => 'nullable|string',
            'bukti_izin' => 'nullable|file|mimes:png,jpg,jpeg,webp|max:10240',
        ], [
            'user_id.unique' => 'Pengguna ini sudah memiliki data absensi ketiga.',
        ]);

        $notes = [];
        if (!empty($validated['catatan_datang'])) {
            $notes[] = 'Datang: ' . $validated['catatan_datang'];
        }
        if (!empty($validated['catatan_pulang'])) {
            $notes[] = 'Pulang: ' . $validated['catatan_pulang'];
        }
        if (!empty($validated['catatan']) && empty($notes)) {
            $notes[] = $validated['catatan'];
        }
        $validated['catatan'] = implode(' | ', $notes);

        $validated['hadir_pagi'] = $request->hadir_pagi ?? 'Belum Absen';
        $validated['hadir_sore'] = $request->hadir_sore ?? 'Belum Absen';

        if ($request->hasFile('bukti_izin')) {
            if ($absenKetiga->bukti_izin) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($absenKetiga->bukti_izin);
            }
            $validated['bukti_izin'] = $request->file('bukti_izin')->store('bukti_izin', 'public');
        }



        // Set waktu untuk semua status (Hadir/Izin/Tidak Hadir) jika belum ada
        if (!empty($validated['hadir_pagi']) && $validated['hadir_pagi'] !== 'Belum Absen' && empty($absenKetiga->waktu_datang)) {
            $validated['waktu_datang'] = now();
        }
        if (!empty($validated['hadir_sore']) && $validated['hadir_sore'] !== 'Belum Absen' && empty($absenKetiga->waktu_pulang)) {
            $validated['waktu_pulang'] = now();
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
