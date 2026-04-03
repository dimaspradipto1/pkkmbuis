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
        return $dataTable->render('pages.user.index');
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
            'nomor_registrasi' => 'required|string|unique:users,nomor_registrasi',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,mahasiswa,stafbaak,pimpinan',
        ], [
            'nomor_registrasi.unique' => 'Nomor registrasi sudah terdaftar.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        $validated = $request->only(['name', 'nomor_registrasi', 'email', 'password', 'role']);

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

        KedisiplinanPertama::create([
            'user_id' => $user->id,
            'kelengkapan_atribut' => '-',
            'ketepatan_waktu' => '-',
            'perilaku' => '-',
            'catatan' => '-',
        ]);

        KedisiplinanKedua::create([
            'user_id' => $user->id,
            'kelengkapan_atribut' => '-',
            'ketepatan_waktu' => '-',
            'perilaku' => '-',
            'catatan' => '-',
        ]);

        KedisiplinanKetiga::create([
            'user_id' => $user->id,
            'kelengkapan_atribut' => '-',
            'ketepatan_waktu' => '-',
            'perilaku' => '-',
            'catatan' => '-',
        ]);

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
            'nomor_registrasi' => [
                'required',
                'string',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:admin,mahasiswa,stafbaak,pimpinan',
        ]);

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
        $headers = ['name', 'email', 'password', 'nomor_registrasi', 'role'];
        $data = [
            ['Jhon Doe', 'jhon@example.com', 'password123', 'REG001', 'mahasiswa'],
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

