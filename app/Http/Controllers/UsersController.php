<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\AbsenKedua;
use App\Models\AbsenKetiga;
use App\Models\AbsenPertama;
use App\Models\KedisiplinanPertama;
use App\Models\KedisiplinanKedua;
use App\Models\KedisiplinanKetiga;
use App\Models\User;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalKakakPendamping = User::where('role', 'kakakpendamping')->count();
        $totalDosenPendamping = User::where('role', 'dosenpendamping')->count();

        return $dataTable->render('pages.user.index', compact('totalMahasiswa', 'totalKakakPendamping', 'totalDosenPendamping'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'id_pendaftar' => 'nullable|string|unique:users,id_pendaftar',
            'nim' => 'nullable|string|max:50|unique:users,nim',
            'nup' => 'nullable|string|max:50|unique:users,nup',
            'email' => 'required|string|email|max:255|unique:users,email',
            'no_wa' => 'nullable|string|max:25|unique:users,no_wa',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,mahasiswa,stafbaak,pimpinan,kakakpendamping,dosenpendamping,timevaluasi,panitia',
            'fakultas' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'jabatan_panitia' => 'nullable|string|max:255',
        ], [
            'id_pendaftar.unique' => 'ID pendaftar sudah terdaftar.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'nup.unique' => 'NUP sudah terdaftar.',
            'email.unique' => 'Email sudah digunakan.',
            'no_wa.unique' => 'Nomor WhatsApp sudah digunakan.',
        ]);

        $validated = $request->only(['name', 'id_pendaftar', 'nim', 'nup', 'email', 'no_wa', 'password', 'role', 'fakultas', 'program_studi', 'jabatan_panitia']);

        if ($validated['role'] === 'panitia') {
            $validated['fakultas'] = '-';
            $validated['program_studi'] = '-';
        } else {
            $validated['jabatan_panitia'] = null;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        AbsenPertama::create([
            'user_id' => $user->id,
            'hadir_pagi' => 'Belum Absen',
            'hadir_sore' => 'Belum Absen',
        ]);

        AbsenKedua::create([
            'user_id' => $user->id,
            'hadir_pagi' => 'Belum Absen',
            'hadir_sore' => 'Belum Absen',
        ]);

        AbsenKetiga::create([
            'user_id' => $user->id,
            'hadir_pagi' => 'Belum Absen',
            'hadir_sore' => 'Belum Absen',
        ]);

        KedisiplinanPertama::firstOrCreate(
            ['user_id' => $user->id],
            [
                'kelengkapan_atribut' => '-',
                'ketepatan_waktu' => '-',
                'perilaku' => '-',
                'catatan' => '-',
            ]
        );

        KedisiplinanKedua::firstOrCreate(
            ['user_id' => $user->id],
            [
                'kelengkapan_atribut' => '-',
                'ketepatan_waktu' => '-',
                'perilaku' => '-',
                'catatan' => '-',
            ]
        );

        KedisiplinanKetiga::firstOrCreate(
            ['user_id' => $user->id],
            [
                'kelengkapan_atribut' => '-',
                'ketepatan_waktu' => '-',
                'perilaku' => '-',
                'catatan' => '-',
            ]
        );

        Alert::success('User berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoclose(4000);
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('pages.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_pendaftar' => [
                'nullable',
                'string',
                Rule::unique('users')->ignore($user->id),
            ],
            'nim' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users')->ignore($user->id),
            ],
            'nup' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'no_wa' => [
                'nullable',
                'string',
                'max:25',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:admin,mahasiswa,stafbaak,pimpinan,kakakpendamping,dosenpendamping,timevaluasi,panitia',
            'fakultas' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'jabatan_panitia' => 'nullable|string|max:255',
        ], [
            'id_pendaftar.unique' => 'ID pendaftar sudah terdaftar.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'nup.unique' => 'NUP sudah terdaftar.',
            'email.unique' => 'Email sudah digunakan.',
            'no_wa.unique' => 'Nomor WhatsApp sudah digunakan.',
        ]);

        if ($validated['role'] === 'panitia') {
            $validated['fakultas'] = '-';
            $validated['program_studi'] = '-';
        } else {
            $validated['jabatan_panitia'] = null;
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);
        Alert::success('User berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoclose(4000);
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            Alert::error('Gagal!', 'Tidak ada user yang dipilih.')->toToast()->autoClose(3000);
            return redirect()->back();
        }

        $ids = array_diff($ids, [Auth::id()]);

        $deleted = User::whereIn('id', $ids)->delete();

        Alert::success('Berhasil!', $deleted . ' user telah dihapus.')->toToast()->autoClose(3000);

        return redirect()->route('users.index');
    }

    public function updatePassword(string $id)
    {
        $user = User::findOrFail($id);
        return view('pages.user.update-password', compact('user'));
    }

    public function updatePasswordPost(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Password user berhasil diperbarui.');
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
            Excel::import(new UsersImport, $request->file('file'));
            Alert::success('Users berhasil diimport.', 'Success')
                ->toToast()
                ->autoclose(4000);
        } catch (\Exception $e) {
            Alert::error('Gagal mengimport data: ' . $e->getMessage(), 'Error')
                ->toToast()
                ->autoclose(4000);
        }

        return redirect()->route('users.index');
    }

    public function downloadTemplate()
    {
        $headers = ['name', 'email', 'no_wa', 'password', 'id_pendaftar', 'nim', 'nup', 'role', 'fakultas', 'program_studi'];
        $data = [
            ['Ahmad Mahasiswa', 'ahmad@example.com', '6281234567890', 'password123', '231061201146', '241061201001', '', 'mahasiswa', 'FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 TEKNIK INFORMATIKA'],
            ['Budi Dosen', 'budi@example.com', '6281298765432', 'password123', '', '', '198701012015011001', 'dosenpendamping', 'FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 TEKNIK INFORMATIKA'],
        ];

        return Excel::download(new class($headers, $data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $headers;
            private $data;
            public function __construct($headers, $data) { $this->headers = $headers; $this->data = $data; }
            public function headings(): array { return $this->headers; }
            public function array(): array { return $this->data; }
        }, 'users_template.xlsx');
    }
}

