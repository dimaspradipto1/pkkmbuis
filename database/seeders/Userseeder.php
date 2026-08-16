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
                'no_wa' => '6281234567890',
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
                'no_wa' => '6281234567891',
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
                'no_wa' => '6281234567892',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
                'fakultas' => 'FAKULTAS ILMU KESEHATAN (FIKes)',
                'program_studi' => 'S2 KESEHATAN MASYARAKAT',
                'is_active' => true,
            ],

            // 3 Dummy Kakak Pendamping
            [
                'name' => 'Budi Santoso',
                'id_pendaftar' => 'KL2026001',
                'email' => 'kakakpendamping@uis.ac.id',
                'no_wa' => '6281234567893',
                'password' => Hash::make('password'),
                'role' => 'kakakpendamping',
                'fakultas' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)',
                'program_studi' => 'S1 TEKNIK INFORMATIKA',
                'is_active' => true,
            ],
            [
                'name' => 'Siti Rahma',
                'id_pendaftar' => 'KL2026002',
                'email' => 'kakakpendamping2@uis.ac.id',
                'no_wa' => '6281234567894',
                'password' => Hash::make('password'),
                'role' => 'kakakpendamping',
                'fakultas' => 'FAKULTAS EKONOMI DAN BISNIS (FEB)',
                'program_studi' => 'S1 MANAJEMEN',
                'is_active' => true,
            ],
            [
                'name' => 'Ahmad Rizky',
                'id_pendaftar' => 'KL2026003',
                'email' => 'kakakpendamping3@uis.ac.id',
                'no_wa' => '6281234567895',
                'password' => Hash::make('password'),
                'role' => 'kakakpendamping',
                'fakultas' => 'FAKULTAS ILMU KESEHATAN (FIKes)',
                'program_studi' => 'S1 KESEHATAN LINGKUNGAN',
                'is_active' => true,
            ],

            // 5 Dosen Pendamping
            [
                'name' => 'Novan Aswadi, S.Kom',
                'id_pendaftar' => 'DP2026001',
                'email' => 'nopanaswadi@uis.ac.id',
                'no_wa' => '6281234567910',
                'password' => Hash::make('password'),
                'role' => 'dosenpendamping',
                'fakultas' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)',
                'program_studi' => 'S1 TEKNIK INFORMATIKA',
                'is_active' => true,
            ],
            [
                'name' => 'Agus Suryadi, M.Kom',
                'id_pendaftar' => 'DP2026002',
                'email' => 'agussuryadi@uis.ac.id',
                'no_wa' => '6281234567911',
                'password' => Hash::make('password'),
                'role' => 'dosenpendamping',
                'fakultas' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)',
                'program_studi' => 'S1 TEKNIK INFORMATIKA',
                'is_active' => true,
            ],
            [
                'name' => 'Sabtu, S.Kom., M.Pd. T',
                'id_pendaftar' => 'DP2026003',
                'email' => 'sabtu@uis.ac.id',
                'no_wa' => '6281234567912',
                'password' => Hash::make('password'),
                'role' => 'dosenpendamping',
                'fakultas' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)',
                'program_studi' => 'S1 TEKNIK INDUSTRI',
                'is_active' => true,
            ],
            [
                'name' => 'Nelma Busra, M.Pd.',
                'id_pendaftar' => 'DP2026004',
                'email' => 'nelmabusra@uis.ac.id',
                'no_wa' => '6281234567913',
                'password' => Hash::make('password'),
                'role' => 'dosenpendamping',
                'fakultas' => 'FAKULTAS EKONOMI DAN BISNIS (FEB)',
                'program_studi' => 'S1 MANAJEMEN',
                'is_active' => true,
            ],
            [
                'name' => 'Khoerun Nisa Safitri, S.T., M.T',
                'id_pendaftar' => 'DP2026005',
                'email' => 'khoerunnisa@uis.ac.id',
                'no_wa' => '6281234567914',
                'password' => Hash::make('password'),
                'role' => 'dosenpendamping',
                'fakultas' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)',
                'program_studi' => 'S1 TEKNIK LOGISTIK',
                'is_active' => true,
            ],

            // Tim Evaluasi
            [
                'name' => 'Dr. Nov Hendri, M.Pd. T',
                'id_pendaftar' => 'TE2026001',
                'email' => 'timevaluasi@uis.ac.id',
                'no_wa' => '6281234567901',
                'password' => Hash::make('password'),
                'role' => 'timevaluasi',
                'fakultas' => 'FAKULTAS Sains dan Teknologi (FST)',
                'program_studi' => 'S1 TEKNIK INFORMATIKA',
                'is_active' => true,
            ],

            // Panitia
            [
                'name' => 'Panitia PKKMB',
                'id_pendaftar' => 'PAN2026001',
                'email' => 'panitia@uis.ac.id',
                'no_wa' => '6281234567999',
                'password' => Hash::make('password'),
                'role' => 'panitia',
                'fakultas' => 'FAKULTAS Sains dan Teknologi (FST)',
                'program_studi' => 'S1 TEKNIK INFORMATIKA',
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
            'Aditya',
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
            $noWa = '628' . str_pad(812345670 + $i, 10, '0', STR_PAD_LEFT);

            $users[] = [
                'name' => $names[$i],
                'id_pendaftar' => $idPendaftar,
                'email' => $email,
                'no_wa' => $noWa,
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'fakultas' => $fakultasProdiList[$i][0],
                'program_studi' => $fakultasProdiList[$i][1],
                'is_active' => true,
            ];
        }

        foreach ($users as $user) {
            User::updateOrCreate(
                ['id_pendaftar' => $user['id_pendaftar']],
                $user
            );
        }
    }
}
