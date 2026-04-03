<?php

namespace App\Http\Controllers;

use App\DataTables\AbsenPertamaDataTable;
use App\Models\AbsenPertama;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AbsenScanController extends Controller
{
    /**
     * Show the scanner page.
     */
    public function index(AbsenPertamaDataTable $dataTable)
    {
        return $dataTable->render('pages.absenpertama.scan');
    }

    /**
     * Process the scanned data.
     */
    /**
     * Process the scanned data.
     */
    public function process(Request $request)
    {
        $barcodeData = $request->barcode_data;
        $currentUser = Auth::user();

        // 1. Logic for MAHASISWA scanning ADMIN QR
        if ($currentUser->role == 'mahasiswa') {
            // Mapping for tokens
            $sessionMap = [
                'ABSEN_1_PAGI' => ['model' => \App\Models\AbsenPertama::class, 'col' => 'hadir_pagi', 'day' => 'Hari I Pagi'],
                'ABSEN_1_SORE' => ['model' => \App\Models\AbsenPertama::class, 'col' => 'hadir_sore', 'day' => 'Hari I Sore'],
                'ABSEN_2_PAGI' => ['model' => \App\Models\AbsenKedua::class, 'col' => 'hadir_pagi', 'day' => 'Hari II Pagi'],
                'ABSEN_2_SORE' => ['model' => \App\Models\AbsenKedua::class, 'col' => 'hadir_sore', 'day' => 'Hari II Sore'],
                'ABSEN_3_PAGI' => ['model' => \App\Models\AbsenKetiga::class, 'col' => 'hadir_pagi', 'day' => 'Hari III Pagi'],
                'ABSEN_3_SORE' => ['model' => \App\Models\AbsenKetiga::class, 'col' => 'hadir_sore', 'day' => 'Hari III Sore'],
            ];

            if (!isset($sessionMap[$barcodeData])) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau bukan merupakan QR Absensi resmi.'
                ], 422);
            }

            $map = $sessionMap[$barcodeData];
            $absen = $map['model']::firstOrCreate(
                ['user_id' => $currentUser->id],
                ['hadir_pagi' => 'Belum Absen', 'hadir_sore' => 'Belum Absen']
            );

            if ($absen->{$map['col']} == 'Hadir') {
                return response()->json([
                    'success' => true,
                    'message' => 'Anda sudah tercatat Hadir untuk sesi ' . $map['day'],
                    'user' => $currentUser
                ]);
            }

            $absen->update([$map['col'] => 'Hadir']);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil! Anda tercatat Hadir pada ' . $map['day'],
                'user' => $currentUser
            ]);
        }

        // 2. Logic for ADMIN scanning MAHASISWA QR (Fallback / Original)
        else {
            $regNumber = $barcodeData ?? $request->nomor_registrasi;
            $targetUser = User::where('nomor_registrasi', $regNumber)->where('role', 'mahasiswa')->first();

            if (!$targetUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mahasiswa tidak ditemukan.'
                ], 404);
            }

            $sesi = $request->sesi; // hadir_pagi or hadir_sore
            if (!$sesi || !in_array($sesi, ['hadir_pagi', 'hadir_sore'])) {
                return response()->json(['success' => false, 'message' => 'Sesi tidak valid.'], 422);
            }

            // For now, default to AbsenPertama for Admin scan if no specific day provided
            // (You might want to add Day selection for Admin in the future)
            $absen = \App\Models\AbsenPertama::firstOrCreate(
                ['user_id' => $targetUser->id],
                ['hadir_pagi' => 'Belum Absen', 'hadir_sore' => 'Belum Absen']
            );

            $absen->update([$sesi => 'Hadir']);

            return response()->json([
                'success' => true,
                'message' => 'Admin mencatat kehadiran: ' . $targetUser->name,
                'user' => $targetUser
            ]);
        }
    }
}
