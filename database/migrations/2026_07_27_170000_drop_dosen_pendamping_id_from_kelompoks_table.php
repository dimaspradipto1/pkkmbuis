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
        if (Schema::hasColumn('kelompoks', 'dosen_pendamping_id')) {
            Schema::table('kelompoks', function (Blueprint $table) {
                $table->dropForeign(['dosen_pendamping_id']);
                $table->dropColumn('dosen_pendamping_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelompoks', function (Blueprint $table) {
            $table->foreignId('dosen_pendamping_id')->nullable()->after('pendamping_id')->constrained('users')->nullOnDelete();
        });
    }
};
