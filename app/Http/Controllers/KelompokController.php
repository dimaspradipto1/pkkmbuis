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
            // Mahasiswa views their own group or all groups
            $myKelompok = $user->kelompok ? $user->kelompok->load(['pendamping', 'anggota']) : null;
            $kelompoks = Kelompok::with(['pendamping', 'anggota'])->withCount('anggota')->get();

            return view('pages.kelompok.student', compact('myKelompok', 'kelompoks'));
        }

        $query = Kelompok::with(['pendamping'])->withCount('anggota');

        if ($user->role == 'kakakleting') {
            // Kakak leting only sees groups where they are assigned as pendamping
            $query->where('pendamping_id', $user->id);
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
        if ($user->role == 'kakakleting') {
            Alert::error('Anda tidak memiliki akses untuk membuat kelompok.', 'Akses Ditolak')
                ->toToast()
                ->autoclose(3000);
            return redirect()->route('kelompok.index');
        }

        $pendampings = User::whereIn('role', ['kakakleting', 'stafbaak', 'admin', 'pimpinan'])->orderBy('name')->get();
        return view('pages.kelompok.create', compact('pendampings'));
    }

    /**
     * Store a newly created kelompok in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role == 'kakakleting') {
            Alert::error('Anda tidak memiliki akses untuk membuat kelompok.', 'Akses Ditolak')
                ->toToast()
                ->autoclose(3000);
            return redirect()->route('kelompok.index');
        }

        $request->validate([
            'nama_kelompok' => 'required|string|max:255|unique:kelompoks,nama_kelompok',
            'pendamping_id' => 'nullable|exists:users,id',
            'keterangan' => 'nullable|string',
        ], [
            'nama_kelompok.unique' => 'Nama kelompok sudah ada.',
        ]);

        Kelompok::create($request->only(['nama_kelompok', 'pendamping_id', 'keterangan']));

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
        $kelompok = Kelompok::with(['pendamping', 'anggota'])->where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        if ($user->role == 'kakakleting' && $kelompok->pendamping_id != $user->id) {
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
        if ($user->role == 'kakakleting') {
            Alert::error('Anda tidak memiliki akses untuk mengubah kelompok.', 'Akses Ditolak')
                ->toToast()
                ->autoclose(3000);
            return redirect()->route('kelompok.index');
        }

        $kelompok = Kelompok::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();
        $pendampings = User::whereIn('role', ['kakakleting', 'stafbaak', 'admin', 'pimpinan'])->orderBy('name')->get();

        return view('pages.kelompok.edit', compact('kelompok', 'pendampings'));
    }

    /**
     * Update the specified kelompok in storage.
     */
    public function update(Request $request, string $slug)
    {
        $user = Auth::user();
        if ($user->role == 'kakakleting') {
            Alert::error('Anda tidak memiliki akses untuk mengubah kelompok.', 'Akses Ditolak')
                ->toToast()
                ->autoclose(3000);
            return redirect()->route('kelompok.index');
        }

        $kelompok = Kelompok::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        $request->validate([
            'nama_kelompok' => 'required|string|max:255|unique:kelompoks,nama_kelompok,' . $kelompok->id,
            'pendamping_id' => 'nullable|exists:users,id',
            'keterangan' => 'nullable|string',
        ]);

        $kelompok->update($request->only(['nama_kelompok', 'pendamping_id', 'keterangan']));

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
        if ($user->role == 'kakakleting') {
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
        $kelompok = Kelompok::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

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
        $kelompok = Kelompok::where('slug', $kelompokSlug)->orWhere('id', $kelompokSlug)->firstOrFail();
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
        $headers = ['id_pendaftar', 'nama_kelompok', 'name', 'fakultas', 'program_studi', 'email'];
        $data = [
            ['010420206', 'Kelompok 1 - Ibnu Sina', 'Mahasiswa Contoh', 'FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 SISTEM INFORMASI', 'mahasiswa@uis.ac.id'],
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
