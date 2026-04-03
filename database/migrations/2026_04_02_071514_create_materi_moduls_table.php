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
        Schema::create('materi_moduls', function (Blueprint $table) {
            $table->id();
            $table->string('modul1')->nullable();
            $table->string('modul2')->nullable();
            $table->string('modul3')->nullable();
            $table->string('modul4')->nullable();
            $table->string('modul5')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_moduls');
    }
};
