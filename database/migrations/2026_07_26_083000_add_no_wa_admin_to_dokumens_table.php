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
        if (Schema::hasTable('dokumens') && !Schema::hasColumn('dokumens', 'no_wa_admin')) {
            Schema::table('dokumens', function (Blueprint $table) {
                $table->string('no_wa_admin')->nullable()->after('link_wa_group');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('dokumens') && Schema::hasColumn('dokumens', 'no_wa_admin')) {
            Schema::table('dokumens', function (Blueprint $table) {
                $table->dropColumn('no_wa_admin');
            });
        }
    }
};
