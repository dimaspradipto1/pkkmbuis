<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluasi_febs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Penilaian Evaluasi Pemateri (q1-q8)
            $table->tinyInteger('q1')->unsigned();
            $table->tinyInteger('q2')->unsigned();
            $table->tinyInteger('q3')->unsigned();
            $table->tinyInteger('q4')->unsigned();
            $table->tinyInteger('q5')->unsigned();
            $table->tinyInteger('q6')->unsigned();
            $table->tinyInteger('q7')->unsigned();
            $table->tinyInteger('q8')->unsigned();

            // Penilaian Evaluasi Isi Materi (q9-q13)
            $table->tinyInteger('q9')->unsigned();
            $table->tinyInteger('q10')->unsigned();
            $table->tinyInteger('q11')->unsigned();
            $table->tinyInteger('q12')->unsigned();
            $table->tinyInteger('q13')->unsigned();

            // Saran dan Masukan kepada Masing-Masing Pemateri
            $table->text('saran_dekan')->nullable();
            $table->text('saran_wakil_dekan_1')->nullable();
            $table->text('saran_wakil_dekan_2')->nullable();
            $table->text('saran_upmi')->nullable();
            $table->text('saran_uppm')->nullable();
            $table->text('saran_prodi_akuntansi')->nullable();
            $table->text('saran_prodi_s1_manajemen')->nullable();
            $table->text('saran_prodi_s2_manajemen')->nullable();
            $table->text('saran_hima_feb')->nullable();

            // Fasilitas dan Penyelenggara (q14-q18)
            $table->tinyInteger('q14')->unsigned();
            $table->tinyInteger('q15')->unsigned();
            $table->tinyInteger('q16')->unsigned();
            $table->tinyInteger('q17')->unsigned();
            $table->tinyInteger('q18')->unsigned();

            // Sarana dan Prasarana (q19-q22)
            $table->tinyInteger('q19')->unsigned();
            $table->tinyInteger('q20')->unsigned();
            $table->tinyInteger('q21')->unsigned();
            $table->tinyInteger('q22')->unsigned();

            // Saran dan Masukan untuk Panitia Pelaksana
            $table->text('saran_panitia')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_febs');
    }
};
