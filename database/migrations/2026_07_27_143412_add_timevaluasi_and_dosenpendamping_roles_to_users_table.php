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
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'mahasiswa', 'kakakpendamping', 'dosenpendamping', 'timevaluasi', 'stafbaak', 'pimpinan') DEFAULT 'mahasiswa'");
        } catch (\Throwable $e) {
            // Ignore if driver does not support direct column modification
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'mahasiswa', 'kakakpendamping', 'stafbaak', 'pimpinan') DEFAULT 'mahasiswa'");
        } catch (\Throwable $e) {
            // Ignore
        }
    }
};
