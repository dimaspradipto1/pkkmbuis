<?php

namespace App\Imports;

use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class KelompokImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $idPendaftar = $row['id_pendaftar'] ?? $row['idpendaftar'] ?? null;
            $namaKelompok = $row['nama_kelompok'] ?? $row['namakelompok'] ?? $row['kelompok'] ?? null;

            if ($idPendaftar && $namaKelompok) {
                // Find or create Kelompok
                $kelompok = Kelompok::firstOrCreate([
                    'nama_kelompok' => trim($namaKelompok),
                ]);

                // Assign Kakak Pendamping by email, if provided
                $kakakPendampingEmail = $row['kakak_pendamping'] ?? $row['kakakpendamping'] ?? null;
                if (!empty($kakakPendampingEmail)) {
                    $kakak = User::where('email', trim($kakakPendampingEmail))->first();
                    if ($kakak) {
                        $kelompok->update(['pendamping_id' => $kakak->id]);
                    }
                }

                // Assign Dosen Pendamping(s) by email, comma/semicolon separated for multiple
                $dosenPendampingRaw = $row['dosen_pendamping'] ?? $row['dosenpendamping'] ?? null;
                if (!empty($dosenPendampingRaw)) {
                    $dosenEmails = array_filter(array_map('trim', preg_split('/[,;]/', $dosenPendampingRaw)));
                    $dosenIds = User::whereIn('email', $dosenEmails)->pluck('id')->all();
                    if (!empty($dosenIds)) {
                        $kelompok->dosenPendampings()->syncWithoutDetaching($dosenIds);
                    }
                }

                // Find student by id_pendaftar or email
                $user = User::where('id_pendaftar', trim($idPendaftar))
                    ->orWhere('email', trim($idPendaftar))
                    ->first();

                if ($user) {
                    $user->update([
                        'kelompok_id' => $kelompok->id,
                        'fakultas' => !empty($row['fakultas']) ? $row['fakultas'] : $user->fakultas,
                        'program_studi' => !empty($row['program_studi']) ? $row['program_studi'] : $user->program_studi,
                    ]);
                } else if (!empty($row['name']) || !empty($row['nama'])) {
                    $name = $row['name'] ?? $row['nama'];
                    User::create([
                        'name' => $name,
                        'id_pendaftar' => trim($idPendaftar),
                        'email' => $row['email'] ?? (trim($idPendaftar) . '@uis.ac.id'),
                        'password' => Hash::make('password123'),
                        'role' => 'mahasiswa',
                        'fakultas' => $row['fakultas'] ?? null,
                        'program_studi' => $row['program_studi'] ?? null,
                        'kelompok_id' => $kelompok->id,
                        'is_active' => 1,
                    ]);
                }
            }
        }
    }
}
