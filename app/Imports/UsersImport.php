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
        $rawWa = isset($row['no_wa']) ? (string)$row['no_wa'] : null;
        if ($rawWa && str_starts_with($rawWa, '08')) {
            $rawWa = '628' . substr($rawWa, 2);
        }

        // Headers expected: name, email, no_wa, password, id_pendaftar, nim, role, fakultas, program_studi
        $user = User::create([
            'name'             => $row['name'],
            'email'            => $row['email'],
            'no_wa'            => $rawWa,
            'password'         => Hash::make($row['password'] ?? 'password123'),
            'id_pendaftar'     => isset($row['id_pendaftar']) && trim((string)$row['id_pendaftar']) !== '' ? trim((string)$row['id_pendaftar']) : null,
            'nim'              => isset($row['nim']) && trim((string)$row['nim']) !== '' ? trim((string)$row['nim']) : null,
            'role'             => $row['role'] ?? 'mahasiswa',
            'fakultas'         => $row['fakultas'] ?? null,
            'program_studi'    => $row['program_studi'] ?? null,
            'is_active'        => 1,
        ]);

        AbsenPertama::firstOrCreate(
            ['user_id' => $user->id],
            ['hadir_pagi' => 'Belum Absen', 'hadir_sore' => 'Belum Absen']
        );

        AbsenKedua::firstOrCreate(
            ['user_id' => $user->id],
            ['hadir_pagi' => 'Belum Absen', 'hadir_sore' => 'Belum Absen']
        );

        AbsenKetiga::firstOrCreate(
            ['user_id' => $user->id],
            ['hadir_pagi' => 'Belum Absen', 'hadir_sore' => 'Belum Absen']
        );

        KedisiplinanPertama::firstOrCreate(
            ['user_id' => $user->id],
            ['kelengkapan_atribut' => '-', 'ketepatan_waktu' => '-', 'perilaku' => '-', 'catatan' => '-']
        );

        KedisiplinanKedua::firstOrCreate(
            ['user_id' => $user->id],
            ['kelengkapan_atribut' => '-', 'ketepatan_waktu' => '-', 'perilaku' => '-', 'catatan' => '-']
        );

        KedisiplinanKetiga::firstOrCreate(
            ['user_id' => $user->id],
            ['kelengkapan_atribut' => '-', 'ketepatan_waktu' => '-', 'perilaku' => '-', 'catatan' => '-']
        );

        return $user;
    }
}
