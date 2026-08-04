<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelompok_kakak_pendamping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->constrained('kelompoks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['kelompok_id', 'user_id']);
        });

        $existingKelompoks = DB::table('kelompoks')->whereNotNull('pendamping_id')->get();
        $now = now();
        foreach ($existingKelompoks as $kelompok) {
            DB::table('kelompok_kakak_pendamping')->insertOrIgnore([
                'kelompok_id' => $kelompok->id,
                'user_id' => $kelompok->pendamping_id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_kakak_pendamping');
    }
};
