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
        Schema::create('absen_settings', function (Blueprint $table) {
            $table->id();
            $table->string('session_code')->unique();
            $table->dateTime('start_time')->nullable();
            $table->integer('duration_minutes')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $sessions = [
            'ABSEN_1_PAGI', 'ABSEN_1_SORE',
            'ABSEN_2_PAGI', 'ABSEN_2_SORE',
            'ABSEN_3_PAGI', 'ABSEN_3_SORE'
        ];

        $now = date('Y-m-d H:i:s');
        foreach ($sessions as $session) {
            DB::table('absen_settings')->insert([
                'session_code' => $session,
                'start_time' => $now,
                'duration_minutes' => 30,
                'is_active' => true,
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
        Schema::dropIfExists('absen_settings');
    }
};
