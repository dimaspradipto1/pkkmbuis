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
        if (Schema::hasTable('absen_pertamas') && !Schema::hasColumn('absen_pertamas', 'catatan')) {
            Schema::table('absen_pertamas', function (Blueprint $table) {
                $table->text('catatan')->nullable()->after('hadir_sore');
            });
        }

        if (Schema::hasTable('absen_keduas') && !Schema::hasColumn('absen_keduas', 'catatan')) {
            Schema::table('absen_keduas', function (Blueprint $table) {
                $table->text('catatan')->nullable()->after('hadir_sore');
            });
        }

        if (Schema::hasTable('absen_ketigas') && !Schema::hasColumn('absen_ketigas', 'catatan')) {
            Schema::table('absen_ketigas', function (Blueprint $table) {
                $table->text('catatan')->nullable()->after('hadir_sore');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('absen_pertamas') && Schema::hasColumn('absen_pertamas', 'catatan')) {
            Schema::table('absen_pertamas', function (Blueprint $table) {
                $table->dropColumn('catatan');
            });
        }

        if (Schema::hasTable('absen_keduas') && Schema::hasColumn('absen_keduas', 'catatan')) {
            Schema::table('absen_keduas', function (Blueprint $table) {
                $table->dropColumn('catatan');
            });
        }

        if (Schema::hasTable('absen_ketigas') && Schema::hasColumn('absen_ketigas', 'catatan')) {
            Schema::table('absen_ketigas', function (Blueprint $table) {
                $table->dropColumn('catatan');
            });
        }
    }
};
