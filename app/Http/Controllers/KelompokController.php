<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\User;
use App\Imports\KelompokImport;
use App\Imports\KelompokAnggotaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KelompokController extends Controller
{
    /**
     * Display a listing of the kelompoks.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'mahasiswa') {
            // Mahasiswa views their own group
            $myKelompok = $user->kelompok ? $user->kelompok->load(['pendamping', 'kakakPendampings', 'dosenPendampings', 'anggota']) : null;

            return view('pages.kelompok.student', compact('myKelompok'));
        }

        $query = Kelompok::with(['pendamping', 'kakakPendampings', 'dosenPendampings'])->withCount('anggota');

        if ($user->role == 'kakakpendamping') {
            // Kakak pendamping sees groups where they are assigned
            $query->where(function ($q) use ($user) {
                $q->where('pendamping_id', $user->id)
                  ->orWhereHas('kakakPendampings', function ($sub) use ($user) {
                      $sub->where('users.id', $user->id);
                  });
            });
        } elseif ($user->role == 'dosenpendamping') {
            // Dosen pendamping only sees groups where they are assigned as dosen pendamping
            $query->whereHas('dosenPendampings', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        $kelompoks = $query->latest()->get();
        return view('pages.kelompok.index', compact('kelompoks'));
    }

    /**
     * Show the form for creating a new kelompok.
     */
    public function create()
    {
        $user = Auth::user();
        if (in_array($user->role, ['kakakpendamping', 'dosenpendamping'])) {
            Alert::error('Anda tidak memiliki akses untuk membuat kelompok.', 'Akses Ditolak')
                ->toToast()
                ->autoclose(3000);
            return redirect()->route('kelompok.index');
        }

        $pendampings = User::whereIn('role', ['kakakpendamping', 'dosenpendamping', 'stafbaak', 'admin', 'pimpinan'])->orderBy('name')->get();
        $dosenPendampingOptions = User::whereIn('role', ['dosenpendamping', 'admin', 'pimpinan'])->orderBy('name')->get();
        return view('pages.kelompok.create', compact('pendampings', 'dosenPendampingOptions'));
    }

    /**
     * Store a newly created kelompok in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (in_array($user->role, ['kakakpendamping', 'dosenpendamping'])) {
            Alert::error('Anda tidak memiliki akses untuk membuat kelompok.', 'Akses Ditolak')
                ->toToast()
                ->autoclose(3000);
            return redirect()->route('kelompok.index');
        }

        $request->validate([
            'nama_kelompok' => 'required|string|max:255|unique:kelompoks,nama_kelompok',
            'pendamping_ids' => 'nullable|array',
            'pendamping_ids.*' => 'exists:users,id',
            'pendamping_id' => 'nullable|exists:users,id',
            'dosen_pendamping_ids' => 'nullable|array',
            'dosen_pendamping_ids.*' => 'exists:users,id',
            'keterangan' => 'nullable|string',
        ], [
            'nama_kelompok.unique' => 'Nama kelompok sudah ada.',
        ]);

        $pendampingIds = $request->input('pendamping_ids', []);
        if ($request->filled('pendamping_id') && empty($pendampingIds)) {
            $pendampingIds = [$request->pendamping_id];
        }

        $kelompok = Kelompok::create([
            'nama_kelompok' => $request->nama_kelompok,
            'pendamping_id' => $pendampingIds[0] ?? null,
            'keterangan' => $request->keterangan,
        ]);

        $kelompok->kakakPendampings()->sync($pendampingIds);
        $kelompok->dosenPendampings()->sync($request->input('dosen_pendamping_ids', []));

        Alert::success('Kelompok berhasil dibuat.', 'Success')
            ->toToast()
            ->autoclose(3000);

        return redirect()->route('kelompok.index');
    }

    /**
     * Display the specified kelompok details and members.
     */
    public function show(string $slug)
    {
        $user = Auth::user();
        $kelompok = Kelompok::with(['pendamping', 'kakakPendampings', 'dosenPendampings', 'anggota', 'notes.user'])->where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        $isKakakDenied = $user->role == 'kakakpendamping' && $kelompok->pendamping_id != $user->id && !$kelompok->kakakPendampings->contains('id', $user->id);
        $isDosenDenied = $user->role == 'dosenpendamping' && !$kelompok->dosenPendampings->contains('id', $user->id);
        $isMahasiswaDenied = $user->role == 'mahasiswa' && $user->kelompok_id != $kelompok->id;

        if ($isKakakDenied || $isDosenDenied || $isMahasiswaDenied) {
            Alert::error('Anda tidak memiliki akses ke kelompok ini.', 'Akses Ditolak')
                ->toToast()
                ->autoclose(4000);
            return redirect()->route('kelompok.index');
        }

        // Get unassigned students (mahasiswa role with no kelompok_id)
        $unassignedStudents = User::where('role', 'mahasiswa')
            ->whereNull('kelompok_id')
            ->orderBy('name')
            ->get();

        return view('pages.kelompok.show', compact('kelompok', 'unassignedStudents'));
    }

    /**
     * Show the form for editing the specified kelompok.
     */
    public function edit(string $slug)
    {
        $user = Auth::user();
        if (in_array($user->role, ['kakakpendamping', 'dosenpendamping'])) {
            Alert::error('Anda tidak memiliki akses untuk mengubah kelompok.', 'Akses Ditolak')
                ->toToast()
                ->autoclose(3000);
            return redirect()->route('kelompok.index');
        }

        $kelompok = Kelompok::with(['kakakPendampings', 'dosenPendampings'])->where('slug', $slug)->orWhere('id', $slug)->firstOrFail();
        $pendampings = User::whereIn('role', ['kakakpendamping', 'dosenpendamping', 'stafbaak', 'admin', 'pimpinan'])->orderBy('name')->get();
        $dosenPendampingOptions = User::whereIn('role', ['dosenpendamping', 'admin', 'pimpinan'])->orderBy('name')->get();

        return view('pages.kelompok.edit', compact('kelompok', 'pendampings', 'dosenPendampingOptions'));
    }

    /**
     * Update the specified kelompok in storage.
     */
    public function update(Request $request, string $slug)
    {
        $user = Auth::user();
        if (in_array($user->role, ['kakakpendamping', 'dosenpendamping'])) {
            Alert::error('Anda tidak memiliki akses untuk mengubah kelompok.', 'Akses Ditolak')
                ->toToast()
                ->autoclose(3000);
            return redirect()->route('kelompok.index');
        }

        $kelompok = Kelompok::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        $request->validate([
            'nama_kelompok' => 'required|string|max:255|unique:kelompoks,nama_kelompok,' . $kelompok->id,
            'pendamping_ids' => 'nullable|array',
            'pendamping_ids.*' => 'exists:users,id',
            'pendamping_id' => 'nullable|exists:users,id',
            'dosen_pendamping_ids' => 'nullable|array',
            'dosen_pendamping_ids.*' => 'exists:users,id',
            'keterangan' => 'nullable|string',
        ]);

        $pendampingIds = $request->input('pendamping_ids', []);
        if ($request->filled('pendamping_id') && empty($pendampingIds)) {
            $pendampingIds = [$request->pendamping_id];
        }

        $kelompok->update([
            'nama_kelompok' => $request->nama_kelompok,
            'pendamping_id' => $pendampingIds[0] ?? null,
            'keterangan' => $request->keterangan,
        ]);

        $kelompok->kakakPendampings()->sync($pendampingIds);
        $kelompok->dosenPendampings()->sync($request->input('dosen_pendamping_ids', []));

        Alert::success('Kelompok berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoclose(3000);

        return redirect()->route('kelompok.index');
    }

    /**
     * Remove the specified kelompok from storage.
     */
    public function destroy(string $slug)
    {
        $user = Auth::user();
        if (in_array($user->role, ['kakakpendamping', 'dosenpendamping'])) {
            Alert::error('Anda tidak memiliki akses untuk menghapus kelompok.', 'Akses Ditolak')
                ->toToast()
                ->autoclose(3000);
            return redirect()->route('kelompok.index');
        }

        $kelompok = Kelompok::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        // Reset kelompok_id for all members
        User::where('kelompok_id', $kelompok->id)->update(['kelompok_id' => null]);

        $kelompok->delete();

        Alert::success('Kelompok berhasil dihapus.', 'Success')
            ->toToast()
            ->autoclose(3000);

        return redirect()->route('kelompok.index');
    }

    /**
     * Add student(s) to a kelompok.
     */
    public function addMember(Request $request, string $slug)
    {
        $user = Auth::user();
        $kelompok = Kelompok::with('dosenPendampings')->where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        $isKakakDenied = $user->role == 'kakakpendamping' && $kelompok->pendamping_id != $user->id;
        $isDosenDenied = $user->role == 'dosenpendamping' && !$kelompok->dosenPendampings->contains('id', $user->id);

        if ($isKakakDenied || $isDosenDenied) {
            abort(403);
        }

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        User::whereIn('id', $request->user_ids)->update(['kelompok_id' => $kelompok->id]);

        Alert::success('Anggota berhasil ditambahkan ke kelompok.', 'Success')
            ->toToast()
            ->autoclose(3000);

        return redirect()->route('kelompok.show', $kelompok->slug);
    }

    /**
     * Remove a student from a kelompok.
     */
    public function removeMember(string $kelompokSlug, string $userId)
    {
        $authUser = Auth::user();

        if (in_array($authUser->role, ['mahasiswa', 'kakakpendamping'])) {
            abort(403);
        }

        $kelompok = Kelompok::with('dosenPendampings')->where('slug', $kelompokSlug)->orWhere('id', $kelompokSlug)->firstOrFail();

        if ($authUser->role == 'dosenpendamping' && !$kelompok->dosenPendampings->contains('id', $authUser->id)) {
            abort(403);
        }

        $user = User::where('id', $userId)->where('kelompok_id', $kelompok->id)->firstOrFail();
        $user->update(['kelompok_id' => null]);

        Alert::success('Anggota berhasil dikeluarkan dari kelompok.', 'Success')
            ->toToast()
            ->autoclose(3000);

        return redirect()->route('kelompok.show', $kelompok->slug);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ], [
            'file.required' => 'File harus diupload.',
            'file.mimes' => 'Format file harus xlsx, xls, atau csv.',
        ]);

        try {
            Excel::import(new KelompokImport, $request->file('file'));
            Alert::success('Data Kelompok berhasil diimport.', 'Success')
                ->toToast()
                ->autoclose(4000);
        } catch (\Exception $e) {
            Alert::error('Gagal mengimport data: ' . $e->getMessage(), 'Error')
                ->toToast()
                ->autoclose(4000);
        }

        return redirect()->route('kelompok.index');
    }

    public function downloadTemplate()
    {
        $headers = ['id_pendaftar', 'nama_kelompok', 'name', 'fakultas', 'program_studi', 'email', 'kakak_pendamping', 'dosen_pendamping'];
        $data = [
            ['010420206', 'Kelompok 1 - Ibnu Sina', 'Mahasiswa Contoh', 'FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 SISTEM INFORMASI', 'mahasiswa@uis.ac.id', 'kakak.pendamping@uis.ac.id', 'dosen1.pendamping@uis.ac.id, dosen2.pendamping@uis.ac.id'],
        ];

        return Excel::download(new class($headers, $data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $headers;
            private $data;
            public function __construct($headers, $data) { $this->headers = $headers; $this->data = $data; }
            public function headings(): array { return $this->headers; }
            public function array(): array { return $this->data; }
        }, 'template_import_kelompok.xlsx');
    }

    public function importMembers(Request $request, string $slug)
    {
        $kelompok = Kelompok::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ], [
            'file.required' => 'File harus diupload.',
            'file.mimes' => 'Format file harus xlsx, xls, atau csv.',
        ]);

        try {
            Excel::import(new KelompokAnggotaImport($kelompok->id), $request->file('file'));
            Alert::success('Anggota berhasil diimport ke ' . $kelompok->nama_kelompok, 'Success')
                ->toToast()
                ->autoclose(4000);
        } catch (\Exception $e) {
            Alert::error('Gagal mengimport anggota: ' . $e->getMessage(), 'Error')
                ->toToast()
                ->autoclose(4000);
        }

        return redirect()->route('kelompok.show', $kelompok->slug);
    }

    public function downloadMemberTemplate(string $slug)
    {
        $headers = ['id_pendaftar', 'name', 'fakultas', 'program_studi', 'email'];
        $data = [
            ['010420206', 'Mahasiswa Contoh', 'FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 SISTEM INFORMASI', 'mahasiswa@uis.ac.id'],
        ];

        return Excel::download(new class($headers, $data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $headers;
            private $data;
            public function __construct($headers, $data) { $this->headers = $headers; $this->data = $data; }
            public function headings(): array { return $this->headers; }
            public function array(): array { return $this->data; }
        }, 'template_import_anggota_kelompok.xlsx');
    }
}
