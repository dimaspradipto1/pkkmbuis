<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Get evaluation statistics (total mahasiswa, total sudah mengisi, total belum mengisi, per-faculty stats).
     *
     * @param string $modelClass
     * @return array
     */
    protected function getEvaluasiStats(string $modelClass): array
    {
        $authUser = Auth::user();
        if ($authUser && $authUser->role === 'kakakpendamping') {
            $myKelompokIds = Kelompok::where('pendamping_id', $authUser->id)
                ->orWhereHas('kakakPendampings', fn($q) => $q->where('users.id', $authUser->id))
                ->pluck('id');
            $userQuery = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds);
        } elseif ($authUser && $authUser->role === 'dosenpendamping') {
            $myKelompokIds = Kelompok::whereHas('dosenPendampings', fn($q) => $q->where('users.id', $authUser->id))->pluck('id');
            $userQuery = User::where('role', 'mahasiswa')->whereIn('kelompok_id', $myKelompokIds);
        } else {
            $userQuery = User::where('role', 'mahasiswa');
        }

        $allStudents = $userQuery->get(['id', 'fakultas']);
        $totalMahasiswa = $allStudents->count();
        $targetUserIds = $allStudents->pluck('id');

        // Check if this is a faculty-specific evaluation (FIKes, FST, FEB)
        $isFacultySpecific = in_array($modelClass, [
            \App\Models\EvaluasiFikes::class,
            \App\Models\EvaluasiFst::class,
            \App\Models\EvaluasiFeb::class,
        ]);

        if ($isFacultySpecific) {
            $facultyCode = match ($modelClass) {
                \App\Models\EvaluasiFikes::class => 'FIKes',
                \App\Models\EvaluasiFst::class => 'FST',
                \App\Models\EvaluasiFeb::class => 'FEB',
                default => null,
            };

            if ($facultyCode) {
                $allStudents = $allStudents->filter(function ($u) use ($facultyCode) {
                    $f = strtoupper($u->fakultas ?? '');
                    if ($facultyCode === 'FIKes') {
                        return str_contains($f, 'FIKES') || str_contains($f, 'KESEHATAN');
                    }
                    if ($facultyCode === 'FST') {
                        return str_contains($f, 'FST') || (str_contains($f, 'SAINS') && str_contains($f, 'TEKNOLOGI'));
                    }
                    if ($facultyCode === 'FEB') {
                        return str_contains($f, 'FEB') || (str_contains($f, 'EKONOMI') && str_contains($f, 'BISNIS'));
                    }
                    return false;
                });
                $totalMahasiswa = $allStudents->count();
                $targetUserIds = $allStudents->pluck('id');
            }
        }

        $submittedUserIds = $modelClass::whereIn('user_id', $targetUserIds)->pluck('user_id')->flip();
        $sudahMengisi = $submittedUserIds->count();
        $belumMengisi = max(0, $totalMahasiswa - $sudahMengisi);

        $facultyStats = [];

        if (!$isFacultySpecific) {
            $faculties = [
                'FST' => [
                    'code' => 'FST',
                    'name' => 'Fakultas Sains & Teknologi',
                    'fullName' => 'Fakultas Sains dan Teknologi (FST)',
                    'color' => '#0284c7',
                    'bg_gradient' => 'linear-gradient(135deg, #0284c7, #0369a1)',
                    'border_color' => '#0284c7',
                    'icon' => 'bi-laptop',
                    'badge_bg' => 'bg-info bg-opacity-10 text-info',
                ],
                'FEB' => [
                    'code' => 'FEB',
                    'name' => 'Fakultas Ekonomi & Bisnis',
                    'fullName' => 'Fakultas Ekonomi dan Bisnis (FEB)',
                    'color' => '#f59e0b',
                    'bg_gradient' => 'linear-gradient(135deg, #f59e0b, #d97706)',
                    'border_color' => '#f59e0b',
                    'icon' => 'bi-graph-up-arrow',
                    'badge_bg' => 'bg-warning bg-opacity-10 text-warning',
                ],
                'FIKes' => [
                    'code' => 'FIKes',
                    'name' => 'Fakultas Ilmu Kesehatan',
                    'fullName' => 'Fakultas Ilmu Kesehatan (FIKes)',
                    'color' => '#10b981',
                    'bg_gradient' => 'linear-gradient(135deg, #10b981, #059669)',
                    'border_color' => '#10b981',
                    'icon' => 'bi-heart-pulse-fill',
                    'badge_bg' => 'bg-success bg-opacity-10 text-success',
                ],
            ];

            foreach ($faculties as $code => $info) {
                $studentsInFak = $allStudents->filter(function ($u) use ($code) {
                    $f = strtoupper($u->fakultas ?? '');
                    if ($code === 'FIKes') {
                        return str_contains($f, 'FIKES') || str_contains($f, 'KESEHATAN');
                    }
                    if ($code === 'FST') {
                        return str_contains($f, 'FST') || (str_contains($f, 'SAINS') && str_contains($f, 'TEKNOLOGI'));
                    }
                    if ($code === 'FEB') {
                        return str_contains($f, 'FEB') || (str_contains($f, 'EKONOMI') && str_contains($f, 'BISNIS'));
                    }
                    return false;
                });

                $fakTotal = $studentsInFak->count();
                $fakSudah = $studentsInFak->filter(fn($u) => isset($submittedUserIds[$u->id]))->count();
                $fakBelum = max(0, $fakTotal - $fakSudah);
                $fakPersen = $fakTotal > 0 ? round(($fakSudah / $fakTotal) * 100, 1) : 0;

                $facultyStats[$code] = array_merge($info, [
                    'total' => $fakTotal,
                    'sudah' => $fakSudah,
                    'belum' => $fakBelum,
                    'persen' => $fakPersen,
                ]);
            }
        }

        return compact('totalMahasiswa', 'sudahMengisi', 'belumMengisi', 'facultyStats', 'isFacultySpecific');
    }
}
