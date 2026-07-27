<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'mahasiswa', 'kakakleting', 'kakakpendamping', 'stafbaak', 'pimpinan') DEFAULT 'mahasiswa'");
        } catch (\Throwable $e) {
            // Ignore if driver doesn't support modify column syntax directly
        }

        DB::table('users')->where('role', 'kakakleting')->update(['role' => 'kakakpendamping']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('role', 'kakakpendamping')->update(['role' => 'kakakleting']);
    }
};
