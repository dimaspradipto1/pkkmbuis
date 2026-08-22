<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Get evaluation statistics (total mahasiswa, total sudah mengisi, total belum mengisi).
     *
     * @param string $modelClass
     * @return array{totalMahasiswa: int, sudahMengisi: int, belumMengisi: int}
     */
    protected function getEvaluasiStats(string $modelClass): array
    {
        $authUser = Auth::user();
        if ($authUser && $authUser->role === 'kakakpendamping') {
            $myKelompokIds = Kelompok::where('pendamping_id', $authUser->id)
                ->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))
                ->pluck('id');
            $targetUserIds = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->pluck('id');
            $totalMahasiswa = $targetUserIds->count();
            $sudahMengisi = $modelClass::whereIn('user_id', $targetUserIds)->count();
        } elseif ($authUser && $authUser->role === 'dosenpendamping') {
            $myKelompokIds = Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
            $targetUserIds = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds)->pluck('id');
            $totalMahasiswa = $targetUserIds->count();
            $sudahMengisi = $modelClass::whereIn('user_id', $targetUserIds)->count();
        } else {
            $totalMahasiswa = User::where('role', 'mahasiswa')->count();
            $sudahMengisi = $modelClass::count();
        }

        $belumMengisi = max(0, $totalMahasiswa - $sudahMengisi);

        return compact('totalMahasiswa', 'sudahMengisi', 'belumMengisi');
    }
}
