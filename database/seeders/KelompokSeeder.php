<?php

namespace Database\Seeders;

use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KelompokSeeder extends Seeder
{
    public function run(): void
    {
        $kl1 = User::where('email', 'kakakleting@uis.ac.id')->first();
        $kl2 = User::where('email', 'kakakleting2@uis.ac.id')->first();
        $kl3 = User::where('email', 'kakakleting3@uis.ac.id')->first();
        $admin = User::where('role', 'admin')->first();

        $k1 = Kelompok::create([
            'nama_kelompok' => 'Kelompok 1 - Ibnu Sina',
            'slug' => Str::slug('Kelompok 1 - Ibnu Sina'),
            'pendamping_id' => $kl1->id ?? $admin->id ?? null,
            'keterangan' => 'Kelompok Mahasiswa Fakultas Sains dan Teknologi',
        ]);

        $k2 = Kelompok::create([
            'nama_kelompok' => 'Kelompok 2 - Al-Khawarizmi',
            'slug' => Str::slug('Kelompok 2 - Al-Khawarizmi'),
            'pendamping_id' => $kl2->id ?? $admin->id ?? null,
            'keterangan' => 'Kelompok Mahasiswa Fakultas Ekonomi dan Bisnis',
        ]);

        $k3 = Kelompok::create([
            'nama_kelompok' => 'Kelompok 3 - Ibnu An-Nafis',
            'slug' => Str::slug('Kelompok 3 - Ibnu An-Nafis'),
            'pendamping_id' => $kl3->id ?? $admin->id ?? null,
            'keterangan' => 'Kelompok Mahasiswa Fakultas Ilmu Kesehatan',
        ]);

        $mahasiswas = User::where('role', 'mahasiswa')->get();

        // Assign 4 students to Kelompok 1
        foreach ($mahasiswas->slice(0, 4) as $mhs) {
            $mhs->update(['kelompok_id' => $k1->id]);
        }

        // Assign 4 students to Kelompok 2
        foreach ($mahasiswas->slice(4, 4) as $mhs) {
            $mhs->update(['kelompok_id' => $k2->id]);
        }

        // Assign 4 students to Kelompok 3
        foreach ($mahasiswas->slice(8, 4) as $mhs) {
            $mhs->update(['kelompok_id' => $k3->id]);
        }

        // The remaining 3 students stay unassigned (kelompok_id = null)
    }
}
