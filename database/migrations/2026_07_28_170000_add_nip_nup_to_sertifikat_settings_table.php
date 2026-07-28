<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sertifikat_settings', function (Blueprint $table) {
            $table->string('nip_mengetahui')->nullable()->default('NIP.196505132005011001')->after('jabatan_mengetahui');
            $table->string('nup_ketua_panitia')->nullable()->default('NUP. 777 0707 688')->after('jabatan_ketua_panitia');
        });
    }

    public function down(): void
    {
        Schema::table('sertifikat_settings', function (Blueprint $table) {
            $table->dropColumn(['nip_mengetahui', 'nup_ketua_panitia']);
        });
    }
};
