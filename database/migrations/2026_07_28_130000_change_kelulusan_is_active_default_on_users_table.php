<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * kelulusan_is_active is a manual "force-publish" override: when true, the mahasiswa
     * dashboard shows the final Lulus/Tidak Lulus result even if not every component
     * (absensi/kedisiplinan/test/tugas/evaluasi) is complete yet. It must default to
     * false so results are only ever force-published when an admin explicitly opts a
     * specific student in.
     */
    public function up(): void
    {
        // Avoid ->change() (requires doctrine/dbal, not installed here): drop + re-add instead.
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kelulusan_is_active');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('kelulusan_is_active')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kelulusan_is_active');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('kelulusan_is_active')->default(true)->after('is_active');
        });
    }
};
