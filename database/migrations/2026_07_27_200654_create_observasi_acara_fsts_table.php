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
        Schema::create('observasi_acara_fsts', function (Blueprint $table) {
            $table->id();
            $table->string('waktu_runddown')->nullable();
            $table->string('waktu_realisasi')->nullable();
            $table->text('kegiatan')->nullable();
            $table->text('aspek_observasi')->nullable();
            $table->integer('skala')->nullable();
            $table->text('catatan')->nullable();
            $table->text('link_dokumen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observasi_acara_fsts');
    }
};
