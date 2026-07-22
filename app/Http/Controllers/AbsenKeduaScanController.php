<?php

namespace App\Http\Controllers;

use App\DataTables\AbsenKeduaDataTable;
use App\Models\AbsenKedua;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AbsenKeduaScanController extends Controller
{
    /**
     * Show the scanner page.
     */
    public function index(AbsenKeduaDataTable $dataTable)
    {
        return $dataTable->render('pages.absenkedua.scan');
    }

    /**
     * Process the scanned data.
     */
    public function process(Request $request)
    {
        $request->validate([
            'id_pendaftar' => 'required|string',
            'sesi' => 'required|in:hadir_pagi,hadir_sore',
        ]);

        $user = User::where('id_pendaftar', $request->id_pendaftar)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna dengan ID pendaftar ' . $request->id_pendaftar . ' tidak ditemukan.'
            ], 404);
        }

        if (Auth::user()->role == 'kakakleting') {
            $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', Auth::id())->pluck('id')->toArray();
            if (!in_array($user->kelompok_id, $myKelompokIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mahasiswa ' . $user->name . ' bukan anggota kelompok Anda.'
                ], 403);
            }
        }

        $absen = AbsenKedua::where('user_id', $user->id)->first();

        if (!$absen) {
            $absen = AbsenKedua::create([
                'user_id' => $user->id,
                'hadir_pagi' => 'Belum Absen',
                'hadir_sore' => 'Belum Absen',
            ]);
        }

        $sesi_name = ($request->sesi == 'hadir_pagi') ? 'Pagi' : 'Sore';

        if ($absen->{$request->sesi} == 'Hadir') {
            return response()->json([
                'success' => true,
                'message' => $user->name . ' sudah melakukan absen ' . $sesi_name . '.',
                'user' => $user
            ]);
        }

        $absen->update([
            $request->sesi => 'Hadir'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Aben ' . $sesi_name . ': ' . $user->name,
            'user' => $user
        ]);
    }
}
