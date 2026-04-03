<?php

namespace App\Imports;

use App\Models\User;
use App\Models\AbsenKedua;
use App\Models\AbsenKetiga;
use App\Models\AbsenPertama;
use App\Models\KedisiplinanPertama;
use App\Models\KedisiplinanKedua;
use App\Models\KedisiplinanKetiga;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class UsersImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Headers expected: name, email, password, nomor_registrasi, role
        $user = User::create([
            'name'             => $row['name'],
            'email'            => $row['email'],
            'password'         => Hash::make($row['password'] ?? 'password123'),
            'nomor_registrasi' => $row['nomor_registrasi'],
            'role'             => $row['role'] ?? 'mahasiswa',
            'is_active'        => 1,
        ]);

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

        return $user;
    }
}


