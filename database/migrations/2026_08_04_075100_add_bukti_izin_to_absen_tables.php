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
        if (Schema::hasTable('absen_pertamas') && !Schema::hasColumn('absen_pertamas', 'bukti_izin')) {
            Schema::table('absen_pertamas', function (Blueprint $table) {
                $table->string('bukti_izin')->nullable()->after('catatan');
            });
        }

        if (Schema::hasTable('absen_keduas') && !Schema::hasColumn('absen_keduas', 'bukti_izin')) {
            Schema::table('absen_keduas', function (Blueprint $table) {
                $table->string('bukti_izin')->nullable()->after('catatan');
            });
        }

        if (Schema::hasTable('absen_ketigas') && !Schema::hasColumn('absen_ketigas', 'bukti_izin')) {
            Schema::table('absen_ketigas', function (Blueprint $table) {
                $table->string('bukti_izin')->nullable()->after('catatan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('absen_pertamas') && Schema::hasColumn('absen_pertamas', 'bukti_izin')) {
            Schema::table('absen_pertamas', function (Blueprint $table) {
                $table->dropColumn('bukti_izin');
            });
        }

        if (Schema::hasTable('absen_keduas') && Schema::hasColumn('absen_keduas', 'bukti_izin')) {
            Schema::table('absen_keduas', function (Blueprint $table) {
                $table->dropColumn('bukti_izin');
            });
        }

        if (Schema::hasTable('absen_ketigas') && Schema::hasColumn('absen_ketigas', 'bukti_izin')) {
            Schema::table('absen_ketigas', function (Blueprint $table) {
                $table->dropColumn('bukti_izin');
            });
        }
    }
};
