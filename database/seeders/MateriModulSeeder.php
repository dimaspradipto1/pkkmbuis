<?php

namespace Database\Seeders;

use App\Models\MateriModul;
use Illuminate\Database\Seeder;

class MateriModulSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 record default dengan semua kolom null
        // Admin dapat mengupdate melalui menu Upload Materi Modul
        MateriModul::create([
            'modul1' => null,
            'modul2' => null,
            'modul3' => null,
            'modul4' => null,
            'modul5' => null,
        ]);
    }
}
