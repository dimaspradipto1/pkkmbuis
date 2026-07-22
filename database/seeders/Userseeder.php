<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Userseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Core Roles
            [
                'name' => 'Admin',
                'id_pendaftar' => '12345',
                'email' => 'admin@uis.ac.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'fakultas' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)',
                'program_studi' => 'S1 TEKNIK INFORMATIKA',
                'is_active' => true,
            ],
            [
                'name' => 'Staf BAAK',
                'id_pendaftar' => '123456',
                'email' => 'baak@usi.ac.id',
                'password' => Hash::make('password'),
                'role' => 'stafbaak',
                'fakultas' => 'FAKULTAS EKONOMI DAN BISNIS (FEB)',
                'program_studi' => 'S1 MANAJEMEN',
                'is_active' => true,
            ],
            [
                'name' => 'Pimpinan',
                'id_pendaftar' => '1234567',
                'email' => 'pimpinan@uis.ac.id',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
                'fakultas' => 'FAKULTAS ILMU KESEHATAN (FIKes)',
                'program_studi' => 'S2 KESEHATAN MASYARAKAT',
                'is_active' => true,
            ],

            // 3 Dummy Kakak Leting
            [
                'name' => 'Kakak Leting 1 (Budi Santoso)',
                'id_pendaftar' => 'KL2026001',
                'email' => 'kakakleting@uis.ac.id',
                'password' => Hash::make('password'),
                'role' => 'kakakleting',
                'fakultas' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)',
                'program_studi' => 'S1 TEKNIK INFORMATIKA',
                'is_active' => true,
            ],
            [
                'name' => 'Kakak Leting 2 (Siti Rahma)',
                'id_pendaftar' => 'KL2026002',
                'email' => 'kakakleting2@uis.ac.id',
                'password' => Hash::make('password'),
                'role' => 'kakakleting',
                'fakultas' => 'FAKULTAS EKONOMI DAN BISNIS (FEB)',
                'program_studi' => 'S1 MANAJEMEN',
                'is_active' => true,
            ],
            [
                'name' => 'Kakak Leting 3 (Ahmad Rizky)',
                'id_pendaftar' => 'KL2026003',
                'email' => 'kakakleting3@uis.ac.id',
                'password' => Hash::make('password'),
                'role' => 'kakakleting',
                'fakultas' => 'FAKULTAS ILMU KESEHATAN (FIKes)',
                'program_studi' => 'S1 KESEHATAN LINGKUNGAN',
                'is_active' => true,
            ],
        ];

        // 15 Dummy Mahasiswa
        $fakultasProdiList = [
            ['FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 TEKNIK INFORMATIKA'],
            ['FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 SISTEM INFORMASI'],
            ['FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 TEKNIK INDUSTRI'],
            ['FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 TEKNIK LOGISTIK'],
            ['FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 TEKNIK PERKAPALAN'],
            ['FAKULTAS EKONOMI DAN BISNIS (FEB)', 'S1 AKUNTANSI'],
            ['FAKULTAS EKONOMI DAN BISNIS (FEB)', 'S1 MANAJEMEN'],
            ['FAKULTAS EKONOMI DAN BISNIS (FEB)', 'S2 MAGISTER MANAJEMEN'],
            ['FAKULTAS ILMU KESEHATAN (FIKes)', 'S1 KESEHATAN DAN KESELAMATAN KERJA'],
            ['FAKULTAS ILMU KESEHATAN (FIKes)', 'S1 KESEHATAN LINGKUNGAN'],
            ['FAKULTAS ILMU KESEHATAN (FIKes)', 'S2 KESEHATAN MASYARAKAT'],
            ['FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 TEKNIK INFORMATIKA'],
            ['FAKULTAS EKONOMI DAN BISNIS (FEB)', 'S1 AKUNTANSI'],
            ['FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'S1 SISTEM INFORMASI'],
            ['FAKULTAS ILMU KESEHATAN (FIKes)', 'S1 KESEHATAN DAN KESELAMATAN KERJA'],
        ];

        $names = [
            'Mahasiswa (Aditya)',
            'Anisa Putri',
            'Bagas Pratama',
            'Citra Dewi',
            'Dimas Saputra',
            'Eka Wulandari',
            'Fajar Hidayat',
            'Gita Gutawa',
            'Hadi Wijaya',
            'Indah Permata',
            'Joko Susilo',
            'Kartika Sari',
            'Lukman Hakim',
            'Maya Angela',
            'Naufal Rizqullah',
        ];

        for ($i = 0; $i < 15; $i++) {
            $idPendaftar = ($i == 0) ? '010420206' : '0104202' . str_pad($i + 10, 2, '0', STR_PAD_LEFT);
            $email = ($i == 0) ? 'mahasiswa@uis.ac.id' : 'mahasiswa' . ($i + 1) . '@uis.ac.id';

            $users[] = [
                'name' => $names[$i],
                'id_pendaftar' => $idPendaftar,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'fakultas' => $fakultasProdiList[$i][0],
                'program_studi' => $fakultasProdiList[$i][1],
                'is_active' => true,
            ];
        }

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
