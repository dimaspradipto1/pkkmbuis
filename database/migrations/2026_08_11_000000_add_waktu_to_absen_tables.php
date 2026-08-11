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
        if (Schema::hasTable('absen_pertamas') && !Schema::hasColumn('absen_pertamas', 'waktu_datang')) {
            Schema::table('absen_pertamas', function (Blueprint $table) {
                $table->timestamp('waktu_datang')->nullable()->after('hadir_pagi');
                $table->timestamp('waktu_pulang')->nullable()->after('hadir_sore');
            });
        }

        if (Schema::hasTable('absen_keduas') && !Schema::hasColumn('absen_keduas', 'waktu_datang')) {
            Schema::table('absen_keduas', function (Blueprint $table) {
                $table->timestamp('waktu_datang')->nullable()->after('hadir_pagi');
                $table->timestamp('waktu_pulang')->nullable()->after('hadir_sore');
            });
        }

        if (Schema::hasTable('absen_ketigas') && !Schema::hasColumn('absen_ketigas', 'waktu_datang')) {
            Schema::table('absen_ketigas', function (Blueprint $table) {
                $table->timestamp('waktu_datang')->nullable()->after('hadir_pagi');
                $table->timestamp('waktu_pulang')->nullable()->after('hadir_sore');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['absen_pertamas', 'absen_keduas', 'absen_ketigas'] as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropColumnIfExists('waktu_datang');
                    $blueprint->dropColumnIfExists('waktu_pulang');
                });
            }
        }
    }
};
