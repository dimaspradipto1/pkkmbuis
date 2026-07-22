<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MateriModul;
use App\Models\AbsenPertama;
use App\Models\KedisiplinanPertama;
use App\Models\SoalPretestPertama;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SystemCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the database
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    /**
     * Test User Authentication (Login).
     */
    public function test_user_authentication()
    {
        // 1. Visit Login Page
        $response = $this->get('/');
        $response->assertStatus(200);

        // 2. Submit Login (Admin Credentials)
        $response = $this->post('/', [
            'login' => '12345',
            'password' => 'password',
        ]);
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test Users CRUD.
     */
    public function test_users_crud()
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Read (List)
        $response = $this->actingAs($admin)->get('/users');
        $response->assertStatus(200);

        // 2. Create (Store)
        $userData = [
            'name' => 'New Mahasiswa Test',
            'id_pendaftar' => 'MHS999',
            'email' => 'mhs999@test.com',
            'password' => 'password123',
            'role' => 'mahasiswa',
        ];
        $response = $this->actingAs($admin)->post('/users', $userData);
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'id_pendaftar' => 'MHS999',
            'email' => 'mhs999@test.com',
        ]);

        $createdUser = User::where('id_pendaftar', 'MHS999')->first();

        // 3. Update (Edit)
        $updateData = [
            'name' => 'Updated Mahasiswa Test',
            'id_pendaftar' => 'MHS999',
            'email' => 'mhs999_updated@test.com',
            'role' => 'mahasiswa',
        ];
        $response = $this->actingAs($admin)->put("/users/{$createdUser->id}", $updateData);
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'id' => $createdUser->id,
            'name' => 'Updated Mahasiswa Test',
            'email' => 'mhs999_updated@test.com',
        ]);

        // 4. Delete (Destroy)
        $response = $this->actingAs($admin)->delete("/users/{$createdUser->id}");
        $response->assertRedirect('/users');
        $this->assertDatabaseMissing('users', [
            'id' => $createdUser->id,
        ]);
    }

    /**
     * Test AbsenPertama CRUD.
     */
    public function test_absen_pertama_crud()
    {
        $admin = User::where('role', 'admin')->first();
        $student = User::where('role', 'mahasiswa')->first();

        // 1. Read (List)
        $response = $this->actingAs($admin)->get('/absenpertama');
        $response->assertStatus(200);

        // 2. Create (Store)
        // Note: A user creation automatically seeds AbsenPertama, but we test direct CRUD here
        $absenData = [
            'user_id' => $student->id,
            'hadir_pagi' => 'Hadir',
            'hadir_sore' => 'Belum Absen',
        ];
        $response = $this->actingAs($admin)->post('/absenpertama', $absenData);
        $response->assertRedirect('/absenpertama');
        $this->assertDatabaseHas('absen_pertamas', [
            'user_id' => $student->id,
            'hadir_pagi' => 'Hadir',
        ]);

        $createdAbsen = AbsenPertama::where('user_id', $student->id)->first();

        // 3. Update (Edit)
        $updateData = [
            'user_id' => $student->id,
            'hadir_pagi' => 'Hadir',
            'hadir_sore' => 'Hadir',
        ];
        $response = $this->actingAs($admin)->put("/absenpertama/{$createdAbsen->id}", $updateData);
        $response->assertRedirect('/absenpertama');
        $this->assertDatabaseHas('absen_pertamas', [
            'id' => $createdAbsen->id,
            'hadir_sore' => 'Hadir',
        ]);

        // 4. Delete (Destroy)
        $response = $this->actingAs($admin)->delete("/absenpertama/{$createdAbsen->id}");
        $response->assertRedirect('/absenpertama');
        $this->assertDatabaseMissing('absen_pertamas', [
            'id' => $createdAbsen->id,
        ]);
    }

    /**
     * Test MateriModul CRUD.
     */
    public function test_materi_modul_crud()
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Read (List)
        $response = $this->actingAs($admin)->get('/materimodul');
        $response->assertStatus(200);

        // Get default seeded MateriModul
        $materi = MateriModul::first();
        $this->assertNotNull($materi);

        // 2. Update (Edit)
        $file1 = \Illuminate\Http\UploadedFile::fake()->create('modul1.pdf', 100);
        $file2 = \Illuminate\Http\UploadedFile::fake()->create('modul2.pdf', 100);

        $updateData = [
            'modul1' => $file1,
            'modul2' => $file2,
        ];
        $response = $this->actingAs($admin)->put("/materimodul/{$materi->id}", $updateData);
        $response->assertRedirect('/materimodul');

        // Verify that the files were stored (database columns are populated with paths)
        $updatedMateri = MateriModul::first();
        $this->assertNotNull($updatedMateri->modul1);
        $this->assertNotNull($updatedMateri->modul2);
        $this->assertStringContainsString('modul1', $updatedMateri->modul1);
    }

    /**
     * Test SoalPretestPertama CRUD.
     */
    public function test_soal_pretest_pertama_crud()
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Read (List)
        $response = $this->actingAs($admin)->get('/soalpretestpertama');
        $response->assertStatus(200);

        // 2. Create (Store)
        $soalData = [
            'soal' => 'Apa ibukota Indonesia?',
            'pilihan_a' => 'Medan',
            'pilihan_b' => 'Jakarta',
            'pilihan_c' => 'Surabaya',
            'pilihan_d' => 'Bandung',
            'jawaban' => 'B',
        ];
        $response = $this->actingAs($admin)->post('/soalpretestpertama', $soalData);
        $response->assertRedirect('/soalpretestpertama');
        $this->assertDatabaseHas('soal_pretest_pertamas', [
            'soal' => 'Apa ibukota Indonesia?',
            'jawaban' => 'B',
        ]);

        $createdSoal = SoalPretestPertama::where('soal', 'Apa ibukota Indonesia?')->first();

        // 3. Update (Edit)
        $updateData = [
            'soal' => 'Apa ibukota Jawa Barat?',
            'pilihan_a' => 'Medan',
            'pilihan_b' => 'Jakarta',
            'pilihan_c' => 'Surabaya',
            'pilihan_d' => 'Bandung',
            'jawaban' => 'D',
        ];
        $response = $this->actingAs($admin)->put("/soalpretestpertama/{$createdSoal->id}", $updateData);
        $response->assertRedirect('/soalpretestpertama');
        $this->assertDatabaseHas('soal_pretest_pertamas', [
            'id' => $createdSoal->id,
            'soal' => 'Apa ibukota Jawa Barat?',
            'jawaban' => 'D',
        ]);

        // 4. Delete (Destroy)
        $response = $this->actingAs($admin)->delete("/soalpretestpertama/{$createdSoal->id}");
        $response->assertRedirect('/soalpretestpertama');
        $this->assertDatabaseMissing('soal_pretest_pertamas', [
            'id' => $createdSoal->id,
        ]);
    }

    /**
     * Test Profile viewing and updating.
     */
    public function test_profile_crud()
    {
        $student = User::where('role', 'mahasiswa')->first();

        // 1. Read profile
        $response = $this->actingAs($student)->get('/profile');
        $response->assertStatus(200);

        // 2. Update profile
        $updateData = [
            'name' => 'Updated Profile Name',
            'email' => 'student_new_email@test.com',
        ];
        $response = $this->actingAs($student)->put('/profile', $updateData);
        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', [
            'id' => $student->id,
            'name' => 'Updated Profile Name',
            'email' => 'student_new_email@test.com',
        ]);
    }

    /**
     * Test Profile Password update.
     */
    public function test_profile_password_update()
    {
        $student = User::where('role', 'mahasiswa')->first();

        // 1. Visit password form
        $response = $this->actingAs($student)->get('/profile/password');
        $response->assertStatus(200);

        // 2. Try with wrong current password
        $response = $this->actingAs($student)->put('/profile/password', [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);
        $response->assertSessionHasErrors('current_password');

        // 3. Update password successfully
        $response = $this->actingAs($student)->put('/profile/password', [
            'current_password' => 'password',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);
        $response->assertRedirect('/profile');

        // Verify we can login with the new password
        $response = $this->post('/', [
            'login' => $student->id_pendaftar,
            'password' => 'newpassword123',
        ]);
        $response->assertRedirect('/dashboard');
    }
}
