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
        Schema::table('kedisiplinan_pertamas', function (Blueprint $table) {
            $table->string('kelengkapan_atribut')->nullable()->change();
            $table->string('ketepatan_waktu')->nullable()->change();
            $table->string('perilaku')->nullable()->change();
        });

        Schema::table('kedisiplinan_keduas', function (Blueprint $table) {
            $table->string('kelengkapan_atribut')->nullable()->change();
            $table->string('ketepatan_waktu')->nullable()->change();
            $table->string('perilaku')->nullable()->change();
        });

        Schema::table('kedisiplinan_ketigas', function (Blueprint $table) {
            $table->string('kelengkapan_atribut')->nullable()->change();
            $table->string('ketepatan_waktu')->nullable()->change();
            $table->string('perilaku')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kedisiplinan_pertamas', function (Blueprint $table) {
            $table->string('kelengkapan_atribut')->nullable(false)->change();
            $table->string('ketepatan_waktu')->nullable(false)->change();
            $table->string('perilaku')->nullable(false)->change();
        });

        Schema::table('kedisiplinan_keduas', function (Blueprint $table) {
            $table->string('kelengkapan_atribut')->nullable(false)->change();
            $table->string('ketepatan_waktu')->nullable(false)->change();
            $table->string('perilaku')->nullable(false)->change();
        });

        Schema::table('kedisiplinan_ketigas', function (Blueprint $table) {
            $table->string('kelengkapan_atribut')->nullable(false)->change();
            $table->string('ketepatan_waktu')->nullable(false)->change();
            $table->string('perilaku')->nullable(false)->change();
        });
    }
};
