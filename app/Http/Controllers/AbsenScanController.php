<?php

namespace App\Http\Controllers;

use App\DataTables\AbsenPertamaDataTable;
use App\Models\AbsenPertama;
use App\Models\AbsenKedua;
use App\Models\AbsenKetiga;
use App\Models\AbsenSetting;
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
     * Get dynamic token and session settings for a session.
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

        $setting = AbsenSetting::firstOrCreate(
            ['session_code' => $session],
            ['start_time' => now(), 'duration_minutes' => 30, 'is_active' => true]
        );

        $isActive = $setting->checkIsActive();

        $timeStep = floor(time() / 60);
        $hash = md5($session . '_' . $timeStep . '_' . config('app.key'));
        $token = $session . ':' . $hash;

        $secondsLeft = 60 - (time() % 60);

        return response()->json([
            'token' => $token,
            'seconds_left' => $secondsLeft,
            'is_active' => $isActive,
            'is_always_active' => (bool) $setting->is_always_active,
            'start_time' => $setting->start_time ? $setting->start_time->format('Y-m-d\TH:i') : null,
            'formatted_start_time' => $setting->start_time ? $setting->start_time->format('d/m/Y H:i') : '-',
            'duration_minutes' => $setting->duration_minutes,
            'remaining_seconds' => $setting->remaining_seconds,
        ]);
    }

    /**
     * Update session attendance settings (start time, duration, is_active).
     */
    public function updateSetting(Request $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'session_code' => 'required|string',
            'start_time' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'is_visible' => 'nullable|boolean',
            'is_always_active' => 'nullable|boolean',
        ]);

        $setting = AbsenSetting::firstOrCreate(
            ['session_code' => $request->session_code],
            ['start_time' => null, 'duration_minutes' => 30, 'is_active' => false, 'is_visible' => true]
        );

        $data = [];

        // Handle duration
        if ($request->has('duration_minutes')) {
            $data['duration_minutes'] = (int) $request->duration_minutes;
        }

        // Handle is_active toggle explicitly
        if ($request->has('is_active')) {
            $isActive = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
            $data['is_active'] = $isActive;
        }

        // Handle is_visible toggle
        if ($request->has('is_visible')) {
            $data['is_visible'] = filter_var($request->is_visible, FILTER_VALIDATE_BOOLEAN);
        }

        // Handle start_time: only update if explicitly provided
        if ($request->filled('start_time')) {
            try {
                $data['start_time'] = \Carbon\Carbon::parse($request->start_time);
            } catch (\Exception $e) {
                // invalid date, ignore
            }
        }

        // Handle is_always_active toggle
        if ($request->has('is_always_active')) {
            $data['is_always_active'] = filter_var($request->is_always_active, FILTER_VALIDATE_BOOLEAN);
        }

        $setting->update($data);
        $setting->refresh();

        // Recheck active status after update (applies 30-min rule)
        $isActiveNow = $setting->checkIsActive();

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan sesi absensi berhasil diperbarui.',
            'is_active_now' => $isActiveNow,
            'is_visible' => (bool) $setting->is_visible,
            'setting' => $setting,
            'remaining_seconds' => $setting->remaining_seconds
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

            // Check if session is active and within 30-minute limit
            $setting = AbsenSetting::where('session_code', $sessionCode)->first();
            if ($setting && !$setting->checkIsActive()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Absensi Gagal! Sesi absensi ini telah ditutup / kadaluwarsa (berakhir 30 menit dari set waktu absensi).'
                ], 422);
            }

            // Verify hash with a broader grace period (-1 to +2 steps = 4 minutes window for clock drift & network lag)
            $timeStep = floor(time() / 60);
            $isValid = false;
            for ($i = -1; $i <= 2; $i++) {
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

            $updateData = [$map['col'] => 'Hadir'];
            // Catat waktu sesuai sesi (datang atau pulang)
            if ($map['col'] === 'hadir_pagi') {
                $updateData['waktu_datang'] = now();
            } else {
                $updateData['waktu_pulang'] = now();
            }
            $absen->update($updateData);

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

            if (Auth::user()->role == 'kakakpendamping') {
                $myKelompokIds = \App\Models\Kelompok::where('pendamping_id', Auth::id())
                    ->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', Auth::id()))
                    ->pluck('id')->toArray();
                if (!in_array($targetUser->kelompok_id, $myKelompokIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mahasiswa ' . $targetUser->name . ' bukan anggota kelompok Anda.'
                    ], 403);
                }
            } elseif (Auth::user()->role == 'dosenpendamping') {
                $myKelompokIds = \App\Models\Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', Auth::id()))
                    ->pluck('id')->toArray();
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

            $updateData = [$sesi => 'Hadir'];
            // Catat waktu sesuai sesi
            if ($sesi === 'hadir_pagi') {
                $updateData['waktu_datang'] = now();
            } else {
                $updateData['waktu_pulang'] = now();
            }
            $absen->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Admin mencatat kehadiran: ' . $targetUser->name,
                'user' => $targetUser
            ]);
        }
    }
}
