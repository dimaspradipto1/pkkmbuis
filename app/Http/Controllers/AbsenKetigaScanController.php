<?php

namespace App\Http\Controllers;

use App\DataTables\AbsenKetigaDataTable;
use App\Models\AbsenKetiga;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AbsenKetigaScanController extends Controller
{
    /**
     * Show the scanner page.
     */
    public function index(AbsenKetigaDataTable $dataTable)
    {
        return $dataTable->render('pages.absenketiga.scan');
    }

    /**
     * Process the scanned data.
     */
    public function process(Request $request)
    {
        $request->validate([
            'nomor_registrasi' => 'required|string',
            'sesi' => 'required|in:hadir_pagi,hadir_sore',
        ]);

        $user = User::where('nomor_registrasi', $request->nomor_registrasi)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna dengan nomor registrasi ' . $request->nomor_registrasi . ' tidak ditemukan.'
            ], 404);
        }

        $absen = AbsenKetiga::where('user_id', $user->id)->first();

        if (!$absen) {
            $absen = AbsenKetiga::create([
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
