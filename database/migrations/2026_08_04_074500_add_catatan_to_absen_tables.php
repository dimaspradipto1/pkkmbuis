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
        foreach (['absen_pertamas', 'absen_keduas', 'absen_ketigas'] as $tbl) {
            if (Schema::hasTable($tbl)) {
                Schema::table($tbl, function (Blueprint $table) use ($tbl) {
                    if (!Schema::hasColumn($tbl, 'catatan')) {
                        $table->text('catatan')->nullable()->after('hadir_sore');
                    }
                    if (!Schema::hasColumn($tbl, 'catatan_datang')) {
                        $table->text('catatan_datang')->nullable()->after('waktu_datang');
                    }
                    if (!Schema::hasColumn($tbl, 'catatan_pulang')) {
                        $table->text('catatan_pulang')->nullable()->after('waktu_pulang');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['absen_pertamas', 'absen_keduas', 'absen_ketigas'] as $tbl) {
            if (Schema::hasTable($tbl)) {
                Schema::table($tbl, function (Blueprint $table) use ($tbl) {
                    if (Schema::hasColumn($tbl, 'catatan_datang')) {
                        $table->dropColumn('catatan_datang');
                    }
                    if (Schema::hasColumn($tbl, 'catatan_pulang')) {
                        $table->dropColumn('catatan_pulang');
                    }
                });
            }
        }
    }
};
