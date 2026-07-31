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
        Schema::table('dokumens', function (Blueprint $table) {
            $table->text('link_tata_tertib_kehidupan_mahasiswa')->nullable()->after('link_rundown');
            $table->text('link_video_tutorial_penggunaan_sistem_PKKMB')->nullable()->after('link_tata_tertib_kehidupan_mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropColumn([
                'link_tata_tertib_kehidupan_mahasiswa',
                'link_video_tutorial_penggunaan_sistem_PKKMB'
            ]);
        });
    }
};
