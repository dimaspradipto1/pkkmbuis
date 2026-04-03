<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            [
                'name' => 'Admin',
                'nomor_registrasi' => '12345',
                'email' => 'admin@uis.ac.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => True,
                'created_at'=>now(),
                'updated_at'=> now(),
            ],
            [
                'name' => 'Staf BAAK',
                'nomor_registrasi' => '123456',
                'email' => 'baak@usi.ac.id',
                'password' => Hash::make('password'),
                'role' => 'stafbaak',
                'is_active' => True,
                'created_at'=>now(),
                'updated_at'=> now(),
            ],
            [
                'name' => 'Pimpinan',
                'nomor_registrasi' => '1234567',
                'email' => 'pimpinan@uis.ac.id',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
                'is_active' => True,
                'created_at'=>now(),
                'updated_at'=> now(),
            ],
            [
                'name' => 'Mahasiswa',
                'nomor_registrasi' => '010420206',
                'email' => 'mahasiswa@uis.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => True,
                'created_at'=>now(),
                'updated_at'=> now(),
            ],
            [
                'name' => 'kakak leting',
                'nomor_registrasi' => '010420207',
                'email' => 'kakakleting@uis.ac.id',
                'password' => Hash::make('password'),
                'role' => 'kakakleting',
                'is_active' => True,
                'created_at'=>now(),
                'updated_at'=> now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
