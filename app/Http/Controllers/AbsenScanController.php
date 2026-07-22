<?php

namespace App\Http\Controllers;

use App\DataTables\AbsenPertamaDataTable;
use App\Models\AbsenPertama;
use App\Models\AbsenKedua;
use App\Models\AbsenKetiga;
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
     * Get dynamic token for a session.
     */
    public function getDynamicToken($session)
    {
        if (Auth::user()->role == 'mahasiswa') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validSessions = [
            'ABSEN_1_PAGI', 'ABSEN_1_SORE',
            'ABSEN_2_PAGI', 'ABSEN_2_SORE',
            'ABSEN_3_PAGI', 'ABSEN_3_SORE'
        ];

        if (!in_array($session, $validSessions)) {
            return response()->json(['error' => 'Invalid session'], 400);
        }

        $timeStep = floor(time() / 60);
        $hash = md5($session . '_' . $timeStep . '_' . config('app.key'));
        $token = $session . ':' . $hash;

        $secondsLeft = 60 - (time() % 60);

        return response()->json([
            'token' => $token,
            'seconds_left' => $secondsLeft
        ]);
    }

    /**
     * Process the scanned data.
     */
    public function process(Request $request)
    {
        $barcodeData = $request->barcode_data;
        $currentUser = Auth::user();

        // 1. Logic for MAHASISWA scanning ADMIN QR
        if ($currentUser->role == 'mahasiswa') {
            $parts = explode(':', $barcodeData);
            if (count($parts) !== 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format QR Code tidak valid.'
                ], 422);
            }

            $sessionCode = $parts[0];
            $scannedHash = $parts[1];

            // Mapping for tokens
            $sessionMap = [
                'ABSEN_1_PAGI' => ['model' => \App\Models\AbsenPertama::class, 'col' => 'hadir_pagi', 'day' => 'Hari I Pagi'],
                'ABSEN_1_SORE' => ['model' => \App\Models\AbsenPertama::class, 'col' => 'hadir_sore', 'day' => 'Hari I Sore'],
                'ABSEN_2_PAGI' => ['model' => \App\Models\AbsenKedua::class, 'col' => 'hadir_pagi', 'day' => 'Hari II Pagi'],
                'ABSEN_2_SORE' => ['model' => \App\Models\AbsenKedua::class, 'col' => 'hadir_sore', 'day' => 'Hari II Sore'],
                'ABSEN_3_PAGI' => ['model' => \App\Models\AbsenKetiga::class, 'col' => 'hadir_pagi', 'day' => 'Hari III Pagi'],
                'ABSEN_3_SORE' => ['model' => \App\Models\AbsenKetiga::class, 'col' => 'hadir_sore', 'day' => 'Hari III Sore'],
            ];

            if (!isset($sessionMap[$sessionCode])) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau bukan merupakan QR Absensi resmi.'
                ], 422);
            }

            // Verify hash with a 1-step grace period
            $timeStep = floor(time() / 60);
            $isValid = false;
            for ($i = 0; $i <= 1; $i++) {
                $checkStep = $timeStep - $i;
                $expectedHash = md5($sessionCode . '_' . $checkStep . '_' . config('app.key'));
                if (hash_equals($expectedHash, $scannedHash)) {
                    $isValid = true;
                    break;
                }
            }

            if (!$isValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code sudah kadaluwarsa (diperbarui setiap 1 menit). Silakan scan QR terbaru.'
                ], 422);
            }

            $map = $sessionMap[$sessionCode];
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
            $regNumber = $barcodeData ?? $request->id_pendaftar;
            $targetUser = User::where('id_pendaftar', $regNumber)->where('role', 'mahasiswa')->first();

            if (!$targetUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mahasiswa tidak ditemukan.'
                ], 404);
            }

            if (Auth::user()->role == 'kakakleting') {
                $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', Auth::id())->pluck('id')->toArray();
                if (!in_array($targetUser->kelompok_id, $myKelompokIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mahasiswa ' . $targetUser->name . ' bukan anggota kelompok Anda.'
                    ], 403);
                }
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
