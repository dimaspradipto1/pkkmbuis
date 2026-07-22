<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class KelompokAnggotaImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    protected $kelompokId;

    public function __construct($kelompokId)
    {
        $this->kelompokId = $kelompokId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $idPendaftar = $row['id_pendaftar'] ?? $row['idpendaftar'] ?? $row['email'] ?? null;

            if ($idPendaftar) {
                $user = User::where('id_pendaftar', trim($idPendaftar))
                    ->orWhere('email', trim($idPendaftar))
                    ->first();

                if ($user) {
                    $user->update([
                        'kelompok_id' => $this->kelompokId,
                        'fakultas' => !empty($row['fakultas']) ? $row['fakultas'] : $user->fakultas,
                        'program_studi' => !empty($row['program_studi']) ? $row['program_studi'] : $user->program_studi,
                    ]);
                } else if (!empty($row['name']) || !empty($row['nama_mahasiswa']) || !empty($row['nama'])) {
                    $name = $row['name'] ?? $row['nama_mahasiswa'] ?? $row['nama'];
                    User::create([
                        'name' => $name,
                        'id_pendaftar' => trim($idPendaftar),
                        'email' => $row['email'] ?? (trim($idPendaftar) . '@uis.ac.id'),
                        'password' => Hash::make('password123'),
                        'role' => 'mahasiswa',
                        'fakultas' => $row['fakultas'] ?? null,
                        'program_studi' => $row['program_studi'] ?? null,
                        'kelompok_id' => $this->kelompokId,
                        'is_active' => 1,
                    ]);
                }
            }
        }
    }
}
