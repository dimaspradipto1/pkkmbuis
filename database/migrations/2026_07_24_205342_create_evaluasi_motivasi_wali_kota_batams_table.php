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
        Schema::create('evaluasi_motivasi_wali_kota_batams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('q1')->unsigned();
            $table->tinyInteger('q2')->unsigned();
            $table->tinyInteger('q3')->unsigned();
            $table->tinyInteger('q4')->unsigned();
            $table->tinyInteger('q5')->unsigned();
            $table->tinyInteger('q6')->unsigned();
            $table->tinyInteger('q7')->unsigned();
            $table->tinyInteger('q8')->unsigned();
            $table->tinyInteger('q9')->unsigned();
            $table->tinyInteger('q10')->unsigned();
            $table->tinyInteger('q11')->unsigned();
            $table->tinyInteger('q12')->unsigned();
            $table->tinyInteger('q13')->unsigned();
            $table->text('saran_dan_masukan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_motivasi_wali_kota_batams');
    }
};
