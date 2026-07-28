<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikat_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('nomor_urut_terakhir')->default(0);
            $table->string('kode_surat')->default('UIS.PKKMB/SF/VII/2026');
            $table->string('nama_kegiatan')->default('PENGENALAN KEHIDUPAN KAMPUS BAGI MAHASISWA BARU (PKKMB) UNIVERSITAS IBNU SINA TAHUN AKADEMIK 2026/2027');
            $table->string('lokasi')->default('Batam');
            $table->string('tanggal_pelaksanaan')->default('21 - 23 Agustus 2026');
            $table->string('nama_mengetahui')->default('Dr. Larisang, S.T., M.T., IPU., ASEAN.Eng');
            $table->string('jabatan_mengetahui')->default('Rektor Universitas Ibnu Sina');
            $table->string('nama_ketua_panitia')->default('Andi Akbar, SE., MM');
            $table->string('jabatan_ketua_panitia')->default('Ketua Panitia PKKMB Tahun 2026-2027');
            $table->string('logo_dikti')->nullable();
            $table->string('logo_belmawa')->nullable();
            $table->string('logo_pkkmb')->nullable();
            $table->string('logo_kampus')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat_settings');
    }
};
