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
        Schema::create('kedisiplinan_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index(); // kedisiplinanpertama, kedisiplinankedua, kedisiplinanketiga
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kedisiplinan_attachments');
    }
};
