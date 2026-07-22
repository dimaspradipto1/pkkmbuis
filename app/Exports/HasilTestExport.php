<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HasilTestExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $users = User::where('role', 'mahasiswa')->with('hasilTests')->get();

        return $users->map(function ($user) {
            return [
                'id_pendaftar' => $user->id_pendaftar,
                'name'             => $user->name,
                'm1_pre'           => $user->hasilTests->where('modul', 1)->where('type', 'pretest')->first()?->skor ?? '',
                'm1_post'          => $user->hasilTests->where('modul', 1)->where('type', 'posttest')->first()?->skor ?? '',
                'm2_pre'           => $user->hasilTests->where('modul', 2)->where('type', 'pretest')->first()?->skor ?? '',
                'm2_post'          => $user->hasilTests->where('modul', 2)->where('type', 'posttest')->first()?->skor ?? '',
                'm3_pre'           => $user->hasilTests->where('modul', 3)->where('type', 'pretest')->first()?->skor ?? '',
                'm3_post'          => $user->hasilTests->where('modul', 3)->where('type', 'posttest')->first()?->skor ?? '',
                'm4_pre'           => $user->hasilTests->where('modul', 4)->where('type', 'pretest')->first()?->skor ?? '',
                'm4_post'          => $user->hasilTests->where('modul', 4)->where('type', 'posttest')->first()?->skor ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'id_pendaftar',
            'name',
            'm1_pre',
            'm1_post',
            'm2_pre',
            'm2_post',
            'm3_pre',
            'm3_post',
            'm4_pre',
            'm4_post',
        ];
    }
}
